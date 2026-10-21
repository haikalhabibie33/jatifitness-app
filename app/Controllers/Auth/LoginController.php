<?php 

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\DTO\LoginDto;
use App\Services\Auth\UserService;

use App\Traits\GenerateAccessToken;

class LoginController extends BaseController
{

    use GenerateAccessToken;
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }


    public function index()
    {
        if (session()->get('admin_logged_in')) {
            return redirect()
                ->to('/admin/dashboard');
        } else {
            return view('pages/web/login');
        }
    }

    public function authenticate()
    {
        log_message('debug', 'AUTHENTICATE HIT');
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $dto = LoginDto::fromPost($this->request->getPost());
        $result = $this->userService->login($dto);
        if (!$result['ok']) {
            return redirect()
                ->back()
                ->with('error', $result['error']);
        }

        $user = $result['data'];

        // Login successful
        session()->set([
            'admin_id' => $user['id'],
            'admin_name' => $user['name'],
            'admin_username' => $user['username'],
            'admin_role' => 'ADMIN',
            'admin_logged_in' => true
        ]);

        if ($dto->remember) {
            return redirect()
                ->to('admin/dashboard')
                ->setCookie([
                    'name' => 'remember_tokens',
                    'value' => $result['token'],
                    'expire' => 60 * 60 * 24 * 30,
                    'path' => '/',
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Lax',
                ])
                ->with('success', 'You have logged in successfully');
        }

        // without remember me checked
        return redirect()
            ->to('/admin/dashboard')
            ->with('success', 'You have logged in successfully');
    }

    public function logout()
    {
        helper('cookie');
        $token = $this->request->getCookie('remember_tokens');
        if ($token){
            $tokenHash = hash('sha256', $token);
            $rememberModel = new \App\Models\RememberTokenModel();

            $rememberModel
                ->where('token_hash', $tokenHash)
                ->delete();
                // delete_cookie('remember_tokens');
        }

        session()->destroy();

        return redirect()
            ->to('/login')
            ->setCookie([
                'name' => 'remember_tokens',
                'value' => '',
                'expire' => -3600,
                'path' => '/',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax',
            ])
            ->with('success', 'You have been logged out successfully');
    }
}