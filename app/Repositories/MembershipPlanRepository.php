<?php

namespace App\Repositories;

use App\Contracts\Repositories\MembershipPlanRepositoryInterface;
use App\Models\MembershipPlanModel;
use App\Traits\RepositoryResponseTrait;
use CodeIgniter\Database\Exceptions\DatabaseException;
use App\DTO\MembershipPlan\PagerViewDto;

class MembershipPlanRepository implements MembershipPlanRepositoryInterface
{
    private MembershipPlanModel $membershipPlanModel;
    use RepositoryResponseTrait;

    public function __construct()
    {
        $this->membershipPlanModel = new MembershipPlanModel();
    }

    public function getMembershipPlanById(int $planId): array
    {
        try {
            $result = $this->membershipPlanModel->find($planId);
            if (!$result) {
                return $this->failedResponse('membership plan not found!');
            }

            return $this->successResponse($result);

        } catch(DatabaseException $e) {
            return $this->failedResponse($e->getMessage());

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function findByCode(string $code): array
    {
        try {
            $result = $this->membershipPlanModel
                ->where('code', $code)
                ->first();

            if (!$result) {
                return $this->failedResponse('membership plan not found!');
            }

            return $this->successResponse($result);

        } catch(DatabaseException $e){
            return $this->failedResponse($e->getMessage());

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function findActivePlan(int $planId): array
    {
        try {
            $result = $this->membershipPlanModel
                    ->where('id', $planId)
                    ->where('is_active', '1')
                    ->first();
        if (!$result) {
            return $this->failedResponse('membership plan not found!');
        }

        return $this->successResponse($result);

        } catch(DatabaseException $e){
            return $this->failedResponse($e->getMessage());

        }catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
        
    }

    public function findAllCasualActivePlans():array
    {
        try {
            $result = $this->membershipPlanModel
                ->where('is_active', 1)
                ->groupStart()
                    ->where('audience_scope', 'CASUAL_ONLY')
                    ->orWhere('audience_scope', 'BOTH')
                ->groupEnd()
            ->findAll();
            // if (!$result) {
            //     return $this->failedResponse($this->membershipPlanModel->errors());
            // }
            return $this->successResponse($result);

        } catch(DatabaseException $e){
            return $this->failedResponse($e->getMessage());

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function findAllMemberActivePlans(): array
    {
        try {
            $result = $this->membershipPlanModel
                ->where('is_active', 1)
                ->groupStart()
                    ->where('audience_scope', 'MEMBER_ONLY')
                    ->orWhere('audience_scope', 'BOTH')
                ->groupEnd()
                ->findAll();
            // if (!$result) {
            //     return $this->failedResponse('membership plan not found!');
            // }
            return $this->successResponse($result);

        } catch(DatabaseException $e){
            return $this->failedResponse($e->getMessage());

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function create(array $data): array
    {
        try {
            $result = $this->membershipPlanModel->insert([
            'code'          => $data['code'],
            'name'          => $data['name'],
            'price'         => $data['price'],
            'duration_days' => $data['duration_days'],
            'description'   => $data['description'],
            'target_member_type' => $data['target_member_type'],
            'audience_scope' => $data['audience_scope'],
            'daily_limit' => $data['daily_limit'],
            'is_active'     => true,
        ],true);

        if (!$result) {
            return $this->failedResponse($this->membershipPlanModel->errors());
        }
        return $this->successResponse(null);

        } catch(DatabaseException $e){
            return $this->failedResponse($e->getMessage());

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function update(array $data): array
    {
        try {
            $result = $this->membershipPlanModel->update($data['id'],
            [
                'code'          => $data['code'],
                'name'          => $data['name'],
                'price'         => $data['price'],
                'duration_days' => $data['duration_days'],
                'description'   => $data['description'],
                'target_member_type' => $data['target_member_type'],
                'audience_scope' => $data['audience_scope'],
                'daily_limit' => $data['daily_limit'],
                'is_active'     => $data['is_active'],
            ]);

            if (!$result) {
                return $this->failedResponse($this->membershipPlanModel->errors());
            }
            return $this->successResponse(null);

        } catch(DatabaseException $e){
            return $this->failedResponse($e->getMessage());

        }catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function findAll(PagerViewDto $dto): array
    {
        try {
            // $result = $this->membershipPlanModel->findAll();
            $result = $this->membershipPlanModel
                ->select('*');


            if (!empty($dto->search)) {
                $result->groupStart()
                    ->like('code', $dto->search)
                    ->orLike('name', $dto->search)
                    ->groupEnd();
            }

            if (!empty($dto->target)) {
                $result->where('target_member_type', $dto->target);
            }

            if (!empty($dto->scope)) {
                $result->where('audience_scope', $dto->scope);
            }

            if (!empty($dto->status)) {
                if ($dto->status === 'INACTIVE') {
                    $result->where('is_active', 0);
                } elseif ($dto->status === 'ACTIVE') {
                    $result->where('is_active', 1);
                }
            }

            $plans = $result->orderBy('id', 'ASC')->paginate($dto->limit, 'default', $dto->page);
            $pager = $result->pager;

            return $this->successResponse([
                'plans' => $plans,
                'pager' => $pager,
            ]);

        } catch(DatabaseException $e){
            return $this->failedResponse($e->getMessage(),[
                'plans' => [],
                'pager' => null
            ]);

        } catch (\Throwable $th) {
            return $this->failedResponse($th->getMessage(),[
                'plans' => [],
                'pager' => null
            ]);
        }
    }

    public function findAllActivePlans(): array
    {
        try {
            $result = $this->membershipPlanModel
                ->where('is_active', 1)
                ->orderBy('price', 'ASC')
                ->findAll();
            // If (!$result) {
            //     return $this->failedResponse($this->membershipPlanModel->errors());
            // }

            return $this->successResponse($result);

        } catch(DatabaseException $e){
            return $this->failedResponse($e->getMessage());

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function delete(int $id): array
    {
        try {
           $result = $this->membershipPlanModel->delete($id);

            if (!$result) {
                return $this->failedResponse($this->membershipPlanModel->errors());
            }

            return $this->successResponse(null);

        } catch(DatabaseException $e){
            return $this->failedResponse($e->getMessage());

        }catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }
}