<?php

namespace App\Contracts\Repositories;

interface DashboardRepositoryInterface
{
    public function getActiveMembers(): array;
    public function getInactiveMembers(): array;
    public function getNewMembers(): array;
    public function getTotalMembers(): array;
    public function chartData(): array;
}