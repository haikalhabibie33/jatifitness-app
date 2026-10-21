<?php

namespace App\Services\Auth;

use App\Contracts\Repositories\RememberTokenRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\DTO\LoginDto;
use App\Models\UserModel;
use App\Models\RememberTokenModel;
use App\Repositories\RememberTokenRepository;
use App\Repositories\UserRepository;

class UserService
{
    protected UserModel $userModel;
    protected RememberTokenModel $rememberTokenModel;
    protected OtpService $otpService;

    private UserRepositoryInterface $userRepository;
    private RememberTokenRepositoryInterface $rememberTokenRepository;

    public function __construct(
        ?UserRepositoryInterface $userRepository = null,
        ?RememberTokenRepositoryInterface $rememberTokenRepository = null,
    )
    {
        $this->userModel          = new UserModel();
        $this->rememberTokenModel = new RememberTokenModel();
        $this->otpService         = new OtpService();

        $this->userRepository = $userRepository ?? new UserRepository();
        $this->rememberTokenRepository = $rememberTokenRepository ?? new RememberTokenRepository();
    }

    public function login(LoginDto $dto): array
    {
        $userData = $this->userRepository->login($dto->username);
        if (!$userData['ok']) {
            return [
                'ok' => false,
                'error' => 'invalid username or password',
            ];
        }
        $user = $userData['data'];
        $password = $user['password'];
        // $user = $userData['data'];
        if (!password_verify($dto->password, $password)) {
            return [
                'ok' => false,
                'error' => 'invalid username or password',
            ];
        }

        $token = bin2hex(random_bytes(32));
        if ($dto->remember === true) {
            $tokenHash = hash('sha256', $token);
            $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));

            $rememberTokenData = [
                'user_id' => $user['id'],
                'token_hash' => $tokenHash,
                'expires_at' => $expiresAt,
                'created_at' => date('Y-m-d H:i:s')
            ];

            // call remember token repository
            $result = $this->rememberTokenRepository->create($rememberTokenData);
            if (!$result['ok']) {
                return [
                    'ok' => false,
                    'error' => 'failed to set remember token!',
                    // 'error' => $result['error'] ===> debugging only
                ];
            }
        }

        return [
            'ok' => true,
            'data' => $user,
            'token' => $token,
        ];
    }

    /**
     * Delete user + manual cascade
     */
    public function deleteUser(int $userId): bool
    {
        // ✅ 1. cleanup OTP
        $this->otpService->deleteUserOtps($userId);

        // ✅ 2. cleanup remember tokens
        $this->rememberTokenModel
             ->where('user_id', $userId)
             ->delete();

        // ✅ 3. delete user
        return $this->userModel->delete($userId);
    }
}