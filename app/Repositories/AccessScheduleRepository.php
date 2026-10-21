<?php

namespace App\Repositories;

use App\Contracts\Repositories\AccessScheduleRepositoryInterface;
use App\Models\AccessScheduleModel;
use App\Traits\RepositoryResponseTrait;
use CodeIgniter\Database\Exceptions\DatabaseException;

class AccessScheduleRepository implements AccessScheduleRepositoryInterface
{
    use RepositoryResponseTrait;

    private AccessScheduleModel $accessScheduleModel;

    public function __construct()
    {
        $this->accessScheduleModel = new AccessScheduleModel();
    }

    public function findAll(
        array $filters,
        int $limit = 10,
        int $page = 1
    ): array {
        try {
            $builder = $this->accessScheduleModel;

            $search = trim((string) ($filters['search'] ?? ''));
            $day = strtoupper(trim((string) ($filters['day'] ?? '')));
            $gender = strtoupper(trim((string) ($filters['gender'] ?? '')));
            $accessType = strtoupper(
                trim((string) ($filters['access_type'] ?? ''))
            );
            $status = strtoupper(trim((string) ($filters['status'] ?? '')));

            if ($search !== '') {
                $builder->like('name', $search);
            }

            if ($day !== '') {
                $builder->where('day_of_week', $day);
            }

            if ($gender !== '') {
                $builder->where('allowed_gender', $gender);
            }

            if ($accessType !== '') {
                $builder->where('access_type', $accessType);
            }

            if ($status === 'ACTIVE') {
                $builder->where('is_active', 1);
            } elseif ($status === 'INACTIVE') {
                $builder->where('is_active', 0);
            }

            $schedules = $builder
                ->orderBy('day_of_week', 'ASC')
                ->orderBy('start_time', 'ASC')
                ->orderBy('priority', 'DESC')
                ->paginate($limit, 'default', $page);

            return $this->successResponse([
                'schedules' => $schedules,
                'pager'     => $builder->pager,
            ]);
        } catch (DatabaseException $e) {
            return $this->failedResponse($e->getMessage(), [
                'schedules' => [],
                'pager'     => null,
            ]);
        } catch (\Throwable $th) {
            return $this->failedResponse($th->getMessage(), [
                'schedules' => [],
                'pager'     => null,
            ]);
        }
    }

    public function findById(int $id): array
    {
        try {
            $schedule = $this->accessScheduleModel
                ->asArray()
                ->find($id);

            if (!$schedule) {
                return $this->failedResponse(
                    'Access schedule not found.',
                    null
                );
            }

            return $this->successResponse($schedule);
        } catch (DatabaseException $e) {
            return $this->failedResponse($e->getMessage(), null);
        } catch (\Throwable $th) {
            return $this->failedResponse($th->getMessage(), null);
        }
    }

    public function create(array $payload): array
    {
        try {
            $scheduleId = $this->accessScheduleModel
                ->insert($payload, true);

            if (!$scheduleId) {
                return $this->failedResponse(
                    $this->accessScheduleModel->errors()
                        ?: 'Failed to create access schedule.',
                    null
                );
            }

            $schedule = $this->accessScheduleModel
                ->asArray()
                ->find($scheduleId);

            return $this->successResponse($schedule);
        } catch (DatabaseException $e) {
            return $this->failedResponse($e->getMessage(), null);
        } catch (\Throwable $th) {
            return $this->failedResponse($th->getMessage(), null);
        }
    }

    public function update(int $id, array $payload): array
    {
        try {
            $existing = $this->accessScheduleModel
                ->asArray()
                ->find($id);

            if (!$existing) {
                return $this->failedResponse(
                    'Access schedule not found.',
                    null
                );
            }

            $updated = $this->accessScheduleModel
                ->update($id, $payload);

            if (!$updated) {
                return $this->failedResponse(
                    $this->accessScheduleModel->errors()
                        ?: 'Failed to update access schedule.',
                    null
                );
            }

            $schedule = $this->accessScheduleModel
                ->asArray()
                ->find($id);

            return $this->successResponse($schedule);
        } catch (DatabaseException $e) {
            return $this->failedResponse($e->getMessage(), null);
        } catch (\Throwable $th) {
            return $this->failedResponse($th->getMessage(), null);
        }
    }

    public function changeStatus(int $id, bool $isActive): array
    {
        try {
            $existing = $this->accessScheduleModel
                ->asArray()
                ->find($id);

            if (!$existing) {
                return $this->failedResponse(
                    'Access schedule not found.',
                    null
                );
            }

            $updated = $this->accessScheduleModel->update($id, [
                'is_active' => $isActive ? 1 : 0,
            ]);

            if (!$updated) {
                return $this->failedResponse(
                    $this->accessScheduleModel->errors()
                        ?: 'Failed to change schedule status.',
                    null
                );
            }

            $schedule = $this->accessScheduleModel
                ->asArray()
                ->find($id);

            return $this->successResponse($schedule);
        } catch (DatabaseException $e) {
            return $this->failedResponse($e->getMessage(), null);
        } catch (\Throwable $th) {
            return $this->failedResponse($th->getMessage(), null);
        }
    }

    public function getSummary(): array
    {
        try {
            $active = $this->accessScheduleModel
                ->where('is_active', 1)
                ->countAllResults();

            $female = $this->accessScheduleModel
                ->where('allowed_gender', 'FEMALE')
                ->countAllResults();

            $member = $this->accessScheduleModel
                ->groupStart()
                    ->where('access_type', 'MEMBER')
                    ->orWhere('access_type', 'ALL')
                ->groupEnd()
                ->countAllResults();

            $unlimited = $this->accessScheduleModel
                ->where('capacity_limit', 0)
                ->countAllResults();

            return $this->successResponse([
                'active'    => $active,
                'female'    => $female,
                'member'    => $member,
                'unlimited' => $unlimited,
            ]);
        } catch (DatabaseException $e) {
            return $this->failedResponse($e->getMessage(), [
                'active'    => 0,
                'female'    => 0,
                'member'    => 0,
                'unlimited' => 0,
            ]);
        } catch (\Throwable $th) {
            return $this->failedResponse($th->getMessage(), [
                'active'    => 0,
                'female'    => 0,
                'member'    => 0,
                'unlimited' => 0,
            ]);
        }
    }

    public function findCurrentSchedule(string $day,string $time,string $accessType): array 
    {
        try {
            $day = strtoupper(trim($day));
            $time = trim($time);
            $accessType = strtoupper(trim($accessType));

            $schedule = $this->accessScheduleModel
                ->asArray()
                ->where('is_active', 1)
                ->where('day_of_week', $day)
                ->where('start_time <=', $time)
                ->where('end_time >=', $time)
                ->groupStart()
                    ->where('access_type', 'ALL')
                    ->orWhere('access_type', $accessType)
                ->groupEnd()
                ->orderBy('priority', 'DESC')
                ->orderBy('start_time', 'DESC')
                ->first();

            if (!$schedule) {
                return $this->failedResponse(
                    'No active access schedule is available at this time.',
                    null
                );
            }

            return $this->successResponse($schedule);

        } catch (DatabaseException $e) {
            return $this->failedResponse(
                $e->getMessage(),
                null
            );

        } catch (\Throwable $th) {
            return $this->failedResponse(
                $th->getMessage(),
                null
            );
        }
    }
}