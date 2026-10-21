<?php 

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\RememberTokenModel;
use App\Models\UserModel;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // dd(session()->get());
        helper('cookie');
        if (session()->get('admin_logged_in')) {
            return;
        }

        $req = service('request');
        $token = $req->getCookie('remember_tokens');

        if (!$token){
            return redirect()->to('/login')
            ->with('error', 'Please log in to access this page.');
        }

        $tokenHash = hash('sha256', $token);

        $rememberModel = new RememberTokenModel();
        $tokenRow = $rememberModel
            ->where('token_hash', $tokenHash)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->first();

        if (!$tokenRow) {
            delete_cookie('remember_tokens');
            return redirect()->to('/login')
                ->with('error', 'Your session has expired. Please log in again.');
        }
        $userModel = new UserModel();
        $user = $userModel->find($tokenRow['user_id']);

        if(!$user) {
            delete_cookie('remember_tokens');
            return redirect()->to('/login')
                ->with('error', 'User not found. Please log in again.');
        }

        //Auto login user
        session()->set([
            'admin_id' => $user['id'],
            'admin_name' => $user['name'],
            'admin_username' => $user['username'],
            'admin_role' => 'ADMIN',
            'admin_logged_in' => true
        ]);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}