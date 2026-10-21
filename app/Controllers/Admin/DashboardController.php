<?php 

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\DashboardService;

class DashboardController extends BaseController
{
    private DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }
    public function index()
    {

        $data = $this->dashboardService->data();
        $errors = [];
        foreach ([
            'activeMembers',
            'newMembers',
            'inactiveMembersThisMonth',
            'totalMembers',
            'chartData',
            ] as $key) {
            if (!$data[$key]['ok']) {
                $errors[$key] = $data[$key]['error'];
            }
        }
        
        if (!empty($errors)) {
            session()->setFlashdata('errors', $errors);
        }

        return view('pages/admin/dashboard', [
            'active' => 'dashboard',
            'activeMembers' => $data['activeMembers']['data'],
            'newMembers' => $data['newMembers']['data'],
            'inactiveMembersThisMonth' => $data['inactiveMembersThisMonth']['data'],
            'totalMembers' => $data['totalMembers']['data'],
            'chartData' => $data['chartData']['data'],
        ]);
    }
}