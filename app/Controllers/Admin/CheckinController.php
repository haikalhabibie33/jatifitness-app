<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\Access\CheckinService;
use App\Traits\RequestValidationTrait;

class CheckinController extends BaseController
{

    private CheckinService $checkinService;

    use RequestValidationTrait;

    public function __construct()
    {
        $this->checkinService = new CheckinService();
    }

    public function index()
    {
        $rules = [
            'token' => 'permit_empty|min_length[32]|max_length[32]|regex_match[/^[A-Za-z0-9+\-\s()]+$/]'
        ];
        if (!$this->validate($rules, $this->request->getGet())) {
            return redirect()
                ->to('admin/checkin')
                ->with('error', 'invalid access token');
        }

        $token = $this->request->getGet('token');
        $data = [
            'token' => $token
        ];

        $results = [];
        if (!empty($token)) {
            $results = $this->checkinService->getAccessFromToken($data);
        }
        
        $results = $this->checkinService->manageStatus($results);
        // dd($results);

        return view('pages/admin/check-in/index', [
            'searchResults' => $results,
            'active'        => 'checkin'
        ]);
    }


    public function confirm()
    {
        helper('url');

        $token = $this->request->getPost('token');
        $result = $this->checkinService->confirm($token);
        if (!$result['ok']) {
            return redirect()
                ->back()
                ->with('error', $result['error']);
        }
        return redirect()
            ->back()
            ->with('success', $result['message']);
    }
}