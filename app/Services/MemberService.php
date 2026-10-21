<?php

namespace App\Services;

use App\Contracts\Repositories\MemberRepositoryInterface;
use App\Contracts\Repositories\MembershipPlanRepositoryInterface;
use App\Contracts\Repositories\MembershipRepositoryInterface;
use App\Contracts\Repositories\MembershipRequestRepositoryInterface;
use App\Repositories\MemberRepository;
use App\Repositories\MembershipPlanRepository;
use App\Repositories\MembershipRepository;
use App\Repositories\MembershipRequestRepository;
use App\DTO\Member\AdminViewListDTO;
use App\DTO\Member\PublicViewListDTO;
use App\Traits\GenerateAccessToken;
use App\Traits\RepositoryResponseTrait;

class MemberService {

    private MemberRepositoryInterface $memberRepository;
    private MembershipRepositoryInterface $membershipRepository;
    private MembershipPlanRepositoryInterface $membershipPlanRepository;
    private MembershipRequestRepositoryInterface $membershipRequestRepository;

    use GenerateAccessToken;
    use RepositoryResponseTrait;

    public function __construct(
        ?MemberRepositoryInterface $memberRepository = null,
        ?MembershipRepositoryInterface $membershipRepository = null,
        ?MembershipPlanRepositoryInterface $membershipPlanRepository = null,
        ?MembershipRequestRepositoryInterface $membershipRequestRepository = null
    )
    {
        $this->memberRepository = $memberRepository ?? new MemberRepository();
        $this->membershipRepository = $membershipRepository ?? new MembershipRepository();
        $this->membershipPlanRepository = $membershipPlanRepository ?? new MembershipPlanRepository();
        $this->membershipRequestRepository = $membershipRequestRepository ?? new MembershipRequestRepository();
    }

    private const MEMBER_RULES =
        [
            'name'           => 'required|max_length[100]',
            'gender'         => 'required|in_list[MALE,FEMALE]',
            'date_of_birth'  => 'required|valid_date[Y-m-d]',
            'phone'          => 'required|max_length[30]',
            'email'          => 'required|valid_email|max_length[100]',
            'address'        => 'required|max_length[255]',
            'member_type'    => 'required|in_list[REGULAR,STUDENT]',
        ];

    public function getMemberRules(): array
    {
        return self::MEMBER_RULES;
    }

    public function findMemberByID(int $id): array
    {
        $result = $this->memberRepository->findById($id);

        if (!$result['ok']) {
            return [
                'success' => false,
                'error' => $result['error'],
            ];
        }
        return [
            'success' => true,
            'data' => $result['data'],
        ];
    }

    public function addNewMember(array $userInput): array
    {
        $phone = $userInput['phone'];
        $email = $userInput['email'];
        $result = $this->memberRepository->checkPhoneAndEmailExists($phone, $email);

        if ($result['ok']) {
            return [
                'success' => false,
                'message' => 'This phone or email exists already',
            ];
        }
        $result = $this->memberRepository->create($userInput);
        if (!$result['ok']) {
            return [
                'success' => false,
                'message' => $result['error'],
            ];
        }
        return [
            'success' => true,
            'message' => 'Successfully create new member',
        ];  
    }

    public function updateMemberData(array $userInput): array
    {
        $id = $userInput['id'];
        $email = $userInput['email'];
        $phone = $userInput['phone'];

        $result = $this->memberRepository->findById($id);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Member not found!'
            ];
        }

        // Before executing this, confirm if input and current phone or email is different
        if ($result['data']['phone'] !== $phone || $result['data']['email'] !== $email) {
            $result = $this->memberRepository->checkPhoneAndEmailExistsDifferentId($id,  $phone, $email);
            if ($result['ok']) {
                return [
                    'success' => false,
                    'message' => 'Phone or email exist on DB! Please change to other email or phone'
                ];
            }
        }

        // Check if member has active membership and want to change member type
        if ($result['data']['member_type'] !== $userInput['member_type']) {
            $isMembershipActive = $this->membershipRepository->isMembershipActive($userInput);
            if ($isMembershipActive['ok']) {
                return [
                    'success' => false,
                    'message' => 'Member with active membership is not allowed to change member type!',
                ];
            }
        }
   
        $result = $this->memberRepository->update($userInput);
        if (!$result['ok']) {
            return [
                'success' => false,
                'message' => $result['error'],
            ];
        }

        return [
            'success' => true,
            'message' => 'Successfully updating member data',
        ];
    }

    public function deactivateMember(int $id): array
    {
        $result = $this->memberRepository->deactivate($id);
        if (!$result['ok']) {
            return [
                'success' => false,
                'message' => $result['error'],
            ];
        }
        return [
            'success' => true,
            'message' => 'successfully deactivate member',
        ];
    }

    public function addMembershipToMember(array $userInput)
    {
        // TODO check if member is valid by id
        $isMemberAvailableById = $this->memberRepository->findById($userInput['id']);
        if (!$isMemberAvailableById['ok']) {
            return [
                'success' => false,
                'message' => 'Member is not exist to get membership!'
            ];
        }
        // TODO validate membership plan is match (active or can be sold, suitable with member type)
        $membershipPlanRow = $this->membershipPlanRepository->findActivePlan($userInput['plan_id']);
        if (!$membershipPlanRow['ok']) {
            return [
                'success' => false,
                'message' => $membershipPlanRow['error'],
            ];
        }

        //TODO check if member type is eligibe to get that membership
        if ($membershipPlanRow['data']['target_member_type'] !== $userInput['target_type']) {
            return [
                'success' => false,
                'message' => 'Member is not valid to get that membership',
            ];
        }
        $paymentSource = match($userInput['payment_method']){
            'CASH'      => 'OFFLINE',
            'TRANSFER'  => 'SYSTEM',
            'QRIS'      => 'ONLINE',
            default     => 'SYSTEM'
        };
        //TODO create membership requests
        $insertData = [
            'access_token'         => $this->generateAccessToken(),
            'request_type'         => 'MEMBER',
            'target_member_type'   => $userInput['target_type'],
            'membership_plan_id'   => $membershipPlanRow['data']['id'],
            'plan_name_snapshot'   => $membershipPlanRow['data']['name'],
            'plan_code_snapshot'   => $membershipPlanRow['data']['code'],
            'price_snapshot'       => $membershipPlanRow['data']['price'],
            'total_price_snapshot' => $membershipPlanRow['data']['price'],
            'daily_limit_snapshot' => $membershipPlanRow['data']['daily_limit'],
            'duration_snapshot'    => $membershipPlanRow['data']['duration_days'],
            'quantity'             => 1,
            'name'                 => $userInput['name'],
            'gender'               => $userInput['gender'],
            'date_of_birth'        => $userInput['date_of_birth'],
            'phone'                => $userInput['phone'],
            'email'                => $userInput['email'],
            'address'              => $userInput['address'],
            'status'               => 'SUBMITTED',
            'approval_required'    => $userInput['target_type'] === 'STUDENT' ? 1 : 0,
            'payment_method'       => $userInput['payment_method'],
            'payment_source'       => $paymentSource,
        ];
        $result = $this->membershipRequestRepository->create($insertData);
        if (!$result['ok']) {
            return [
                'success' => false,
                'message' => $result['error'],
            ];
        }
        return [
            'success' => true,
            'message' => 'successfully creating membership request, please proceed it to payment',
        ];
    }

    public function isMemberAvailable(array $data): array
    {
        // if (!$data['phone'] || !$data['email']) {
        //     return [
        //         'ok' => false, 
        //         'message' => 'Invalid input',
        //         'debug' => null
        //         ];
        // }
        $existing = $this->memberRepository->checkPhoneAndEmailExists($data['phone'], $data['email']);
        if ($existing['ok']) {
            return [
                'ok' => false, 
                'message' => 'Existing phone or email has been registered',
                'debug' => null
                ];
        }

        return [
            'ok' => true,
            'message' => null,
            'debug' => 'I am alive'];
    }

    // API Services
    public function findAllAdmin(): array
    {
        $result = $this->memberRepository->findAll();
        if (!$result['ok']) {
            return $this->failedResponse('No member found!', []);
        }

        return $this->successResponse(AdminViewListDTO::collection($result['data']));
    }

    public function findAllPublic(): array
    {
        $result = $this->memberRepository->findAll();
        if (!$result['ok']) {
            return $this->failedResponse('No member found!', []);
        }

        return $this->successResponse(PublicViewListDTO::collection($result['data']));
    }

    public function findByIDAdmin(int $id): array
    {
        $result = $this->memberRepository->findById($id);
        if (!$result['ok']) {
            return $this->failedResponse($result['error']);
        }

        return $this->successResponse(AdminViewListDTO::array($result['data']));
    }

    public function findByIDPublic(int $id): array
    {
        $result = $this->memberRepository->findById($id);
        if (!$result['ok']) {
            return $this->failedResponse($result['error']);
        }

        return $this->successResponse(PublicViewListDTO::array($result['data']));
    }

    public function findByPhoneAndEmailAdmin(string $phone, string $email): array
    {
        $result = $this->memberRepository->checkPhoneAndEmailExists($phone, $email);
        if (!$result['ok']) {
            return $this->failedResponse('No member found!');
        }

        return $this->successResponse(AdminViewListDTO::array($result['data']));
    }

    public function findByPhoneAndEmailPublic(string $phone, string $email): array
    {
        $result = $this->memberRepository->checkPhoneAndEmailExists($phone, $email);
        if (!$result['ok']) {
            return $this->failedResponse('No member found!');
        }

        return $this->successResponse(PublicViewListDTO::array($result['data']));
    }
}