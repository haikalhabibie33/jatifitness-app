<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;

class UserController extends BaseController
{
    public function index(): string
    {
        return view('pages/web/login');
    }
    public function forgotPassword(): string
    {
        return view('pages/web/forgot-password');
    }
    public function otpVerification(): string 
    {
        return view('pages/web/otp-verification');
    }
    public function postAdmin(): string
    {
        // TODO: this function should handle login to admin page
        return view('pages/admin/dashboard',['active' => 'dashboard']);
    }
    public function getAdmin(): string
    {
        // TODO: this function should handle when user trying to access admin page
        return view('pages/admin/dashboard', ['active' => 'dashboard']);
    }

    public function getAbout() : string {
        // TODO: this function should handle when user trying to access about in admin menu
        return view('pages/admin/about', ['active' => 'about']);
    }
}
