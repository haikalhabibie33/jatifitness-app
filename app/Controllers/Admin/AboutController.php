<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AboutController extends BaseController
{
    public function index()
    {
        return view('pages/admin/about', [
            'active' => 'about'
        ]);
    }
}