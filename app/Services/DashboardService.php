<?php

namespace App\Services;

use App\Contracts\Repositories\DashboardRepositoryInterface;
use App\Repositories\DashboardRepository;

class DashboardService
{
    private DashboardRepositoryInterface $dashboardRepository;

    public function __construct(
        ?DashboardRepositoryInterface $dashboardRepository = null,
    )
    {
        $this->dashboardRepository = $dashboardRepository ?? new DashboardRepository();
    }

    public function data()
    {
        $activeMembers = $this->dashboardRepository->getActiveMembers();
        $newMembers = $this->dashboardRepository->getNewMembers();
        $inactiveMembersThisMonth = $this->dashboardRepository->getInactiveMembers();
        $totalMembers = $this->dashboardRepository->getTotalMembers();
        $chartData = $this->dashboardRepository->chartData();

        return [
            'activeMembers' => $activeMembers,
            'newMembers' => $newMembers,
            'inactiveMembersThisMonth' => $inactiveMembersThisMonth,
            'totalMembers' => $totalMembers,
            'chartData' => $chartData
        ];
    }


}