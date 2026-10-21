<?php 

namespace App\Services\Access;

use App\Contracts\Repositories\AccessLogRepositoryInterface;
use App\Contracts\Repositories\CasualPassRepositoryInterface;
use App\Contracts\Repositories\MemberAccessRepositoryInterface;
use App\Models\AccessLogModel;
use App\Models\CasualPassModel;
use App\Models\MemberAccessModel;
use App\Repositories\AccessLogRepository;
use App\Repositories\CasualPassRepository;
use App\Repositories\MemberAccessRepository;
use CodeIgniter\Database\Exceptions\DatabaseException;
use App\DTO\Checkin\AccessHistoryQueryDto;
use App\Traits\RepositoryResponseTrait;
use App\Contracts\Repositories\AccessScheduleRepositoryInterface;
use App\Repositories\AccessScheduleRepository;

class CheckinService 
{

    private MemberAccessModel $memberAccessModel;
    private CasualPassModel $casualPassModel;
    private AccessLogModel $accessLogModel;

    use RepositoryResponseTrait;
    private MemberAccessRepositoryInterface $memberAccessRepository;
    private CasualPassRepositoryInterface $casualPassRepository;
    private AccessLogRepositoryInterface $accessLogRepository;
    private AccessScheduleRepositoryInterface $accessScheduleRepository;

    public function __construct(
        ?MemberAccessRepositoryInterface $memberAccessRepository = null,
        ?CasualPassRepositoryInterface $casualPassRepository = null,
        ?AccessLogRepositoryInterface $accessLogRepository = null,
        ?AccessScheduleRepositoryInterface $accessScheduleRepository = null,
    )
    {
        $this->memberAccessModel = new MemberAccessModel();
        $this->casualPassModel = new CasualPassModel();
        $this->accessLogModel = new AccessLogModel();

        $this->memberAccessRepository = $memberAccessRepository ?? new MemberAccessRepository();
        $this->casualPassRepository = $casualPassRepository ?? new CasualPassRepository();
        $this->accessLogRepository = $accessLogRepository ?? new AccessLogRepository();
        $this->accessScheduleRepository = $accessScheduleRepository ?? new AccessScheduleRepository();
    }

    public function getAccessFromToken(array $data): array
    {
        $results = [];
        $member = $this->memberAccessRepository->getMemberAccessByToken($data['token']);
        if ($member['ok']) {
            $results[] = [
                'type' => 'MEMBER',
                'data' => $member['data'],
            ];
        }

        $casual = $this->casualPassRepository->getCasualPassByToken($data['token']);
        if ($casual['ok']) {
            $results[] = [
                'type' =>'CASUAL',
                'data' => $casual['data']
            ];
        }
        return $results;
    }

    public function manageStatus(array $results)
    {
        foreach ($results as &$item) {

            $token = $item['data']['access_token'] ?? null;

            if (!$token) {
                $item['state'] = 'UNKNOWN';
                continue;
            }

            if (($item['type'] ?? '') === 'CASUAL') {
                $dailyLimit = (int) ($item['data']['daily_limit'] ?? 1);
                $usageLimit = (int) ($item['data']['usage_limit'] ?? 1);

                /*
                * Prioritas pertama:
                * Jika masih ada sesi terbuka, user harus bisa check-out.
                */
                $openLog = $this->accessLogModel
                    ->where('access_token', $token)
                    ->where('check_out_at', null)
                    ->orderBy('check_in_at', 'DESC')
                    ->first();

                if ($openLog) {
                    $item['state'] = 'OUT';
                    continue;
                }

                /*
                * Hitung seluruh sesi yang sudah dimulai selama pass berlaku.
                * Setiap row access_logs mewakili satu sesi pemakaian.
                */
                $totalUsage = $this->accessLogModel
                    ->where('access_token', $token)
                    ->countAllResults();

                if ($usageLimit > 0 && $totalUsage >= $usageLimit) {
                    $item['state'] = 'DONE';
                    $item['state_reason'] = 'USAGE_LIMIT_REACHED';
                    continue;
                }

                /*
                * Hitung sesi yang sudah dimulai hari ini.
                */
                $todayUsage = $this->accessLogModel
                    ->where('access_token', $token)
                    ->where('check_in_at >=', date('Y-m-d') . ' 00:00:00')
                    ->where('check_in_at <=', date('Y-m-d') . ' 23:59:59')
                    ->countAllResults();

                if ($dailyLimit > 0 && $todayUsage >= $dailyLimit) {
                    $item['state'] = 'DONE';
                    $item['state_reason'] = 'DAILY_LIMIT_REACHED';
                    continue;
                }

                $item['state'] = 'IN';
                $item['state_reason'] = null;
                continue;
            }

            /*
            * =========================
            * MEMBER STATUS
            * =========================
            */

            $dailyLimit = (int) ($item['data']['daily_limit'] ?? 1);
            $today = date('Y-m-d');

            /*
            * Jika masih ada sesi terbuka dari tanggal mana pun,
            * next action harus CHECK-OUT.
            *
            * Check-out tidak dibatasi schedule atau capacity.
            */
            $latestLog = $this->accessLogModel
                ->where('access_token', $token)
                ->orderBy('check_in_at', 'DESC')
                ->first();

            $openLog = $this->accessLogModel
                ->where('access_token', $token)
                ->where('check_out_at', null)
                ->orderBy('check_in_at', 'DESC')
                ->first();

            
            if ($openLog) {
                $item['state'] = 'OUT';
                $item['state_reason'] = null;
                continue;
            }

            /*
            * Bentuk data agar sama dengan parameter yang dibutuhkan
            * oleh validateCurrentSchedule().
            */
            $scheduleData = [
                'type' => 'MEMBER',
                'gender' => strtoupper(
                    trim((string) ($item['data']['gender'] ?? ''))
                ),
            ];

            $scheduleValidation = $this->validateCurrentSchedule($scheduleData);

            // dd([
            //     'branch' => 'MEMBER MANAGE STATUS',
            //     'token' => $token,
            //     'daily_limit' => $dailyLimit,
            //     'latest_log' => $latestLog,
            //     'open_log' => $openLog,
            //     'today' => $today,
            //     'schedule_data' => $scheduleData,
            //     'schedule_validation' => $scheduleValidation,
            // ]);

            if (!$scheduleValidation['success']) {
                $item['state'] = 'BLOCKED';
                $item['state_reason'] = $scheduleValidation['message'];
                continue;
            }

            /*
            * Hitung seluruh sesi yang dimulai hari ini.
            * Satu row access_logs dihitung sebagai satu sesi.
            */
            $todayUsage = $this->accessLogModel
                ->where('access_token', $token)
                ->where('check_in_at >=', $today . ' 00:00:00')
                ->where('check_in_at <=', $today . ' 23:59:59')
                ->countAllResults();

            if ($dailyLimit > 0 && $todayUsage >= $dailyLimit) {
                $item['state'] = 'DONE';
                $item['state_reason'] = 'DAILY_LIMIT_REACHED';
                continue;
            }

            $item['state'] = 'IN';
            $item['state_reason'] = null;
        }
        unset($item);
        return $results;
    }

    public function confirm(string $token):array
    {
        $db = \Config\Database::connect();
        $db->transBegin();
        try {
            $isDetermineUser = $this->determineUser($token);
            if (!$isDetermineUser['success']) {
                throw new \RuntimeException($isDetermineUser['message']);
            }
            $determineUser = $isDetermineUser['data'];
            $isManageLog = $this->manageLog($token, $determineUser);
            if (!$isManageLog['success']) {
                throw new \RuntimeException($isManageLog['message']);
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }

            $db->transCommit();
            return [
                'ok' => true,
                'message' => $isManageLog['message'],
            ];

        } catch(DatabaseException $e){
            $db->transRollback();
            return [
                'ok' => false,
                'error' => $e->getMessage()
            ];
        } catch (\Throwable $th) {
            $db->transRollback();
            return [
                'ok' => false,
                'error' => $th->getMessage()
            ];
        }
        
    }

    private function determineUser(string $token): array
    {
        $member = $this->memberAccessModel
            ->select('member_access.*,
                      memberships.daily_limit,
                      members.gender')
            ->join('memberships', 'memberships.id = member_access.membership_id', 'LEFT')
            ->join('members','members.id = memberships.member_id', 'LEFT')
            ->where('access_token', $token)
            ->first();
        if ($member) {
            if ($member['status'] !== 'ACTIVE') {
                return [
                    'success' => false,
                    'message' => 'Member not active',
                    'data'    => []
                ];
            }

            $gender = strtoupper(trim((string) ($member['gender'] ?? '')));
            if (!in_array($gender, ['MALE','FEMALE'], true)) {
                return [
                    'success' => false,
                    'message' => 'Member gender information is unavailable',
                    'data'    => [],
                ];
            }

            $type = 'MEMBER';
            $id = $member['membership_id'];
            $limit = $member['daily_limit'] ?? 1;

            return [
                'success' => true,
                'message' => 'user is a member!',
                'data' => [
                    'type'      => $type,
                    'id'        => $id,
                    'limit'     => $limit,
                    'gender'    => strtoupper((string) ($member['gender'] ?? '')),
                ]
            ];

        } else {
            $casual =$this->casualPassModel
                    ->select('casual_passes.*, 
                              membership_requests.gender,
                              membership_requests.duration_snapshot')
                    ->join('membership_requests','casual_passes.membership_request_id = membership_requests.id')
                    ->where('casual_passes.access_token', $token)
                    ->first();

            if (!$casual) {
                return [
                    'success' => false,
                    'message' => 'Token not found',
                    'data'    => []
                ];
            }

            $gender = strtoupper(trim((string) ($casual['gender'] ?? '')));
            if (!in_array($gender, ['MALE','FEMALE'], true)){
                return [
                    'success' => false,
                    'message' => 'Casual pass gender information is unavailable',
                    'data'    => [],
                ];
            }

            $status = strtoupper(trim($casual['status'] ?? ''));

            if ($status === 'UNUSED') {
                $durationDays = (int) ($casual['duration_snapshot'] ?? 0);
                if ($durationDays <= 0) {
                    return [
                        'success' => false,
                        'message' => 'Invalid casual pass duration',
                        'data'    => []
                    ];
                }

                $now = date('Y-m-d H:i:s');
                $activated = $this->casualPassModel->update($casual['id'], [
                    'activated_at'  => $now,
                    'expires_at'    => date(
                        'Y-m-d H:i:s',
                        strtotime("+{$durationDays} days")
                    ),
                    'status'        => 'ACTIVE'
                    ]
                );

                if (!$activated) {
                    return [
                        'success'   => false,
                        'message'   => 'Failed to activate casual pass',
                        'data'      => [],
                    ];
                }

            } elseif ($status === 'ACTIVE') {
                if (!empty($casual['expires_at']) && strtotime($casual['expires_at']) < time()) {
                    return [
                        'success' => false,
                        'message' => 'Pass expired',
                        'data'    => []
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Invalid pass',
                    'data'    => []
                ];
            }
            
            return [
                'success' => true,
                'message' => 'User is casual pass!',
                'data'   => [
                    'type'        => 'CASUAL',
                    'id'          => (int) $casual['id'],
                    'daily_limit' => (int) ($casual['daily_limit'] ?? 1),
                    'usage_limit' => (int) ($casual['usage_limit'] ?? 1),
                    'gender'      => $gender,
                ]
            ];
        }

    }

    private function validateCurrentSchedule(array $data): array
    {
        $accessType = strtoupper((string) ($data['type'] ?? ''));
        $gender = strtoupper((string) ($data['gender'] ?? ''));

        if (!in_array($accessType, ['MEMBER', 'CASUAL'], true)) {
            return [
                'success' => false,
                'message' => 'Invalid access type.',
            ];
        }

        if (!in_array($gender, ['MALE', 'FEMALE'], true)) {
            return [
                'success' => false,
                'message' => 'Gender information is required for scheduled access.',
            ];
        }

        $day = strtoupper(date('l'));
        $time = date('H:i:s');

        $scheduleResult = $this->accessScheduleRepository
            ->findCurrentSchedule(
                day: $day,
                time: $time,
                accessType: $accessType,
            );

        if (!$scheduleResult['ok']) {
            return [
                'success' => false,
                'message' => 'Check-in is unavailable outside the active schedule.',
            ];
        }

        $schedule = $scheduleResult['data'];

        $allowedGender = strtoupper(
            (string) ($schedule['allowed_gender'] ?? 'ALL')
        );

        if (
            $allowedGender !== 'ALL'
            && $allowedGender !== $gender
        ) {
            return [
                'success' => false,
                'message' => 'This schedule is restricted by gender.',
            ];
        }

        $capacityLimit = (int) ($schedule['capacity_limit'] ?? 0);

        if ($capacityLimit > 0) {
            $currentOccupancy = $this->accessLogModel
                ->where('check_out_at', null)
                ->countAllResults();

            if ($currentOccupancy >= $capacityLimit) {
                return [
                    'success' => false,
                    'message' => 'Gym capacity has been reached.',
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'Schedule access allowed.',
            'data' => [
                'schedule' => $schedule,
            ],
        ];
    }

    private function manageLog(string $token, array $data): array
    {
        if (($data['type'] ?? '') === 'CASUAL') {
            $dailyLimit = (int) ($data['daily_limit'] ?? 1);
            $usageLimit = (int) ($data['usage_limit'] ?? 1);
            $today = date('Y-m-d');

            /*
            * Check-out harus diprioritaskan.
            * Limit tidak boleh menghalangi sesi yang masih terbuka.
            */
            $openLog = $this->accessLogModel
                ->where('access_token', $token)
                ->where('check_out_at', null)
                ->orderBy('check_in_at', 'DESC')
                ->first();

            if ($openLog) {
                $updated = $this->accessLogModel->update($openLog['id'], [
                    'check_out_at' => date('Y-m-d H:i:s'),
                ]);

                if (!$updated) {
                    return [
                        'success' => false,
                        'message' => 'Failed to check out.',
                    ];
                }

                return [
                    'success' => true,
                    'message' => 'Check-out ✅',
                ];
            }

            $scheduleValidation = $this->validateCurrentSchedule($data);
            if (!$scheduleValidation['success']){
                return $scheduleValidation;
            }

            /*
            * Total penggunaan selama pass berlaku.
            * Satu row access_logs dihitung sebagai satu sesi.
            */
            $totalUsage = $this->accessLogModel
                ->where('access_token', $token)
                ->countAllResults();

            if ($usageLimit > 0 && $totalUsage >= $usageLimit) {
                return [
                    'success' => false,
                    'message' => 'Casual pass usage limit reached.',
                ];
            }

            /*
            * Penggunaan pada tanggal hari ini.
            */
            $todayUsage = $this->accessLogModel
                ->where('access_token', $token)
                ->where('check_in_at >=', $today . ' 00:00:00')
                ->where('check_in_at <=', $today . ' 23:59:59')
                ->countAllResults();

            if ($dailyLimit > 0 && $todayUsage >= $dailyLimit) {
                return [
                    'success' => false,
                    'message' => 'Daily check-in limit reached.',
                ];
            }

            $inserted = $this->accessLogModel->insert([
                'access_token' => $token,
                'type'         => 'CASUAL',
                'ref_id'       => $data['id'],
                'check_in_at'  => date('Y-m-d H:i:s'),
            ]);

            if (!$inserted) {
                return [
                    'success' => false,
                    'message' => 'Failed to check in.',
                ];
            }

            return [
                'success' => true,
                'message' => 'Check-in ✅',
            ];
        }

        /*
        * =========================
        * MEMBER ACCESS
        * =========================
        */

        $dailyLimit = (int) ($data['limit'] ?? 1);
        $today = date('Y-m-d');

        /*
        * Prioritas pertama:
        * Cari sesi terbuka dari tanggal mana pun.
        *
        * Check-out tidak boleh dibatasi schedule, gender,
        * capacity, atau daily limit.
        */
        $openLog = $this->accessLogModel
            ->where('access_token', $token)
            ->where('check_out_at', null)
            ->orderBy('check_in_at', 'DESC')
            ->first();

        if ($openLog) {
            $updated = $this->accessLogModel->update($openLog['id'], [
                'check_out_at' => date('Y-m-d H:i:s'),
            ]);

            if (!$updated) {
                return [
                    'success' => false,
                    'message' => 'Failed to check out.',
                ];
            }

            return [
                'success' => true,
                'message' => 'Check-out ✅',
            ];
        }

        /*
        * Tidak ada open session.
        * Berarti action berikutnya adalah check-in baru.
        *
        * Validasi:
        * - schedule tersedia
        * - access type sesuai
        * - gender sesuai
        * - capacity belum penuh
        */
        $scheduleValidation = $this->validateCurrentSchedule($data);

        if (!$scheduleValidation['success']) {
            return $scheduleValidation;
        }

        /*
        * Hitung penggunaan MEMBER pada hari ini.
        *
        * Satu row access_logs berarti satu sesi yang sudah dimulai.
        */
        $todayUsage = $this->accessLogModel
            ->where('access_token', $token)
            ->where('check_in_at >=', $today . ' 00:00:00')
            ->where('check_in_at <=', $today . ' 23:59:59')
            ->countAllResults();

        if ($dailyLimit > 0 && $todayUsage >= $dailyLimit) {
            return [
                'success' => false,
                'message' => 'Daily check-in limit reached.',
            ];
        }

        /*
        * Semua validasi lolos.
        * Buat sesi check-in baru.
        */
        $inserted = $this->accessLogModel->insert([
            'access_token' => $token,
            'type'         => 'MEMBER',
            'ref_id'       => $data['id'],
            'check_in_at'  => date('Y-m-d H:i:s'),
        ]);

        if (!$inserted) {
            return [
                'success' => false,
                'message' => 'Failed to check in.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Check-in ✅',
        ];
    }

    public function history(AccessHistoryQueryDto $dto): array
    {
        $result = $this->accessLogRepository->findAccessHistory(
            $dto->toFilterArray(),
            $dto->limit,
            $dto->offset()
        );

        if (!$result['ok']) {
            return [
                'ok' => false,
                'data' => [
                    'histories' => [],
                    'pager' => null,
                    'page' => $dto->page,
                    'limit' => $dto->limit,
                    'total' => 0,   
                ],
            ];
        }

        $pager = service('pager');
        $pager->makeLinks(
            $dto->page,
            $dto->limit,
            $result['data']['total'],
            'bootstrap_custom'
        );

        return $this->successResponse([
            'histories' => $result['data']['rows'],
            'total' => $result['data']['total'],
            'page' => $dto->page,
            'limit' => $dto->limit,
            'pager' => $pager,
        ]);
    }
}