<?php

namespace App\Repositories;

use App\Contracts\Repositories\DashboardRepositoryInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;
use App\Traits\RepositoryResponseTrait;

class DashboardRepository implements DashboardRepositoryInterface
{
    use RepositoryResponseTrait;

    public function getActiveMembers(): array
    {
        /*
        * Active members:
        * member yang punya latest membership,
        * status ACTIVE,
        * start_date <= today,
        * end_date >= today.
        */
        try {
            $db = \Config\Database::connect();
            $today = date('Y-m-d');
            // $currentMonth = date('m');
            // $currentYear = date('Y');

            $latestMembershipSubquery = $db->table('memberships')
            ->select('member_id, MAX(end_date) AS latest_end_date')
            ->groupBy('member_id')
            ->getCompiledSelect();

            $activeMembersRow = $db->table('members m')
            ->select('COUNT(DISTINCT m.id) AS total')
            ->join(
            "($latestMembershipSubquery) latest_membership",
            'latest_membership.member_id = m.id',
            'INNER',
            false
            )
            ->join(
            'memberships ms',
            'ms.member_id = latest_membership.member_id
            AND ms.end_date = latest_membership.latest_end_date',
            'INNER',
            false
            )
            ->where('ms.status', 'ACTIVE')
            ->where('ms.start_date <=', $today)
            ->where('ms.end_date >=', $today)
            ->get()
            ->getRowArray();

            $activeMembers = (int) ($activeMembersRow['total']);
            return $this->successResponse($activeMembers);
            
        } catch(DatabaseException $e){
            return $this->failedResponse($e->getMessage(),0);

        } catch (\Throwable $th) {
            return $this->failedResponse($th->getMessage(),0);

        }
        
    }

    public function getInactiveMembers(): array
    {
        /*
        * Inactive members:
        * member yang punya latest membership,
        * tapi latest membership-nya sudah tidak valid.
        *
        * Catatan:
        * end_date < today lebih aman daripada <= today,
        * karena kalau end_date hari ini biasanya masih valid sampai hari ini.
        */
        try {
            $db = \Config\Database::connect();
            $today = date('Y-m-d');
            // $currentMonth = date('m');
            // $currentYear = date('Y');

            $latestMembershipSubquery = $db->table('memberships')
            ->select('member_id, MAX(end_date) AS latest_end_date')
            ->groupBy('member_id')
            ->getCompiledSelect();

            $inactiveMembersRow = $db->table('members m')
            ->select('COUNT(DISTINCT m.id) AS total')
            ->join(
            "($latestMembershipSubquery) latest_membership",
            'latest_membership.member_id = m.id',
            'INNER',
            false
            )
            ->join(
            'memberships ms',
            'ms.member_id = latest_membership.member_id
            AND ms.end_date = latest_membership.latest_end_date',
            'INNER',
            false
            )
            ->groupStart()
            ->where('ms.status !=', 'ACTIVE')
            ->orWhere('ms.start_date >', $today)
            ->orWhere('ms.end_date <', $today)
            ->groupEnd()
            ->get()
            ->getRowArray();
            $inactiveMembersThisMonth = (int) ($inactiveMembersRow['total']);
            return $this->successResponse($inactiveMembersThisMonth);

        } catch(DatabaseException $e){
            return $this->failedResponse($e->getMessage(),0);

        } catch (\Throwable $th) {
            return $this->failedResponse($th->getMessage(),0);

        }
    }

    public function getNewMembers(): array
    {
        /*
        * New members this month:
        * berdasarkan members.created_at, bukan memberships.start_date.
        */
        try {
            $db = \Config\Database::connect();
            // $today = date('Y-m-d');
            $currentMonth = date('m');
            $currentYear = date('Y');

            $newMembersRow = $db->table('members')
            ->select('COUNT(*) AS total')
            ->where('MONTH(created_at)', $currentMonth, false)
            ->where('YEAR(created_at)', $currentYear, false)
            ->get()
            ->getRowArray();

            $newMembers = (int) ($newMembersRow['total']);
            return $this->successResponse($newMembers);

        } catch(DatabaseException $e){
            return $this->failedResponse($e->getMessage(),0);

        } catch (\Throwable $th) {
            return $this->failedResponse($th->getMessage(),0);

        }
    }

    public function getTotalMembers(): array
    {
        /*
        * Total members:
        * semua member di table members.
        */
        try {
            $db = \Config\Database::connect();

            $totalMembersRow = $db->table('members')
            ->select('COUNT(*) AS total')
            ->get()
            ->getRowArray();

            $totalMembers = (int) ($totalMembersRow['total']);
            
            return $this->successResponse($totalMembers);

        } catch(DatabaseException $e){
            return $this->failedResponse($e->getMessage(),0);

        } catch (\Throwable $th) {
            return $this->failedResponse($th->getMessage(),0);
        }
    }   

    public function chartData(): array
    {
        try {
            /*
            * Chart:
            * member baru per bulan.
            */
            $db = \Config\Database::connect();
            // $today = date('Y-m-d');
            // $currentMonth = date('m');
            // $currentYear = date('Y');

            $chartData = $db->table('members')
            ->select('YEAR(created_at) AS year, MONTH(created_at) AS month, COUNT(*) AS total')
            ->groupBy('YEAR(created_at), MONTH(created_at)')
            ->orderBy('YEAR(created_at)', 'ASC')
            ->orderBy('MONTH(created_at)', 'ASC')
            ->get()
            ->getResultArray();

            return $this->successResponse($chartData);

        } catch(DatabaseException $e){
            return $this->failedResponse($e->getMessage(),[]);

        } catch (\Throwable $th) {
            return $this->failedResponse($th->getMessage(),[]);
        }
    }
}