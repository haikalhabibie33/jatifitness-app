<?php

namespace App\Repositories;

use App\Contracts\Repositories\MemberRepositoryInterface;
use App\Models\MemberModel;
use App\Traits\RepositoryResponseTrait;
use CodeIgniter\Database\Exceptions\DatabaseException;

class MemberRepository implements MemberRepositoryInterface
{
    
    use RepositoryResponseTrait;
    private MemberModel $memberModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
    }

    public function findAll(): array
    {
        try {
            $result = $this->memberModel
                ->asArray()
                ->orderBy('id', 'ASC')
                ->findAll();
            // if (!$result) {
            //     return $this->failedResponse($this->memberModel->errors());
            // }
            return $this->successResponse($result);

        } catch(DatabaseException $e){
            return $this->failedResponse($e->getMessage());

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function findById(int $id): array
    { 
        try {
            $result = $this->memberModel->find($id);
            if (!$result) {
                return $this->failedResponse($this->memberModel->errors());
            }

            return $this->successResponse($result);
        } catch(DatabaseException $e){
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function checkPhoneAndEmailExists(string $phone, string $email): array
    {
        try {
            $member = $this->memberModel
                ->groupStart()
                    ->where('phone', $phone)
                    ->orWhere('email', $email)
                ->groupEnd()
                ->where('deleted_at', null)
                ->first();

            if (!$member) {
                return $this->failedResponse($this->memberModel->errors());
            }
            return $this->successResponse($member);

        } catch(DatabaseException $e){
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function checkPhoneAndEmailExistsDifferentId(int $id, string $phone, string $email): array
    {
        try {
            $member = $this->memberModel
                ->groupStart()
                    ->where('phone', $phone)
                    ->orWhere('email', $email)
                ->groupEnd()
                ->where('id !=', $id)
                ->first();
            if (!$member) {
                return $this->failedResponse($this->memberModel->errors());
            }
            return $this->successResponse($member);

        } catch(DatabaseException $e){
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }

    public function create(array $userInput): array
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        $db->transBegin();
        try {

            $memberInserted = $this->memberModel->insert([
                        'name' => $userInput['name'],
                        'gender' => $userInput['gender'],
                        'date_of_birth' => $userInput['date_of_birth'],
                        'phone' => $userInput['phone'],
                        'email' => $userInput['email'],
                        'address' => $userInput['address'],
                        'member_type' => $userInput['member_type'],
                        'is_active' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
            ],true);

            if (!$memberInserted) { 
                throw new \RuntimeException('Failed to add member!');
                }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            return $this->successResponse($memberInserted);

        } catch(DatabaseException $e){
            $db->transRollback();
            return $this->failFromThrowable($e);

        } catch(\Throwable $e) {
            $db->transRollback();
            return $this->failFromThrowable($e);
        }
    }

    public function update(array $userInput): array
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        $db->transBegin();
        try {
            $memberUpdated = $this->memberModel->update($userInput['id'], [
            'name'          => $userInput['name'],
            'gender'        => $userInput['gender'],
            'date_of_birth' => $userInput['date_of_birth'],
            'phone'         => $userInput['phone'],
            'email'         => $userInput['email'],
            'address'       => $userInput['address'],
            'member_type'   => $userInput['member_type'],
            'is_active'     => $userInput['is_active'] ? true : false,
            'updated_at'    => $now,
            ]);

            if (!$memberUpdated) { 
                throw new \RuntimeException('Failed to update member data!');
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }

            $db->transCommit();

            return $this->successResponse(null);

        } catch(DatabaseException $e){
            $db->transRollback();
            return $this->failFromThrowable($e);

        } catch(\Throwable $e) {
            $db->transRollback();
            return $this->failFromThrowable($e);
        }
    }

    public function deactivate(int $memberid): array
    {
        try {
            $result = $this->memberModel->update($memberid,[
                'is_active' => false,
            ]);

            if(!$result) {
                return $this->failedResponse($this->memberModel->errors());
            }
            
            return $this->successResponse(null);

        } catch(DatabaseException $e){
            return $this->failFromThrowable($e);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }
}