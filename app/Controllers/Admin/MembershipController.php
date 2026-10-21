<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MembershipModel;
use App\Models\MemberModel;
use App\Models\MembershipPlanModel;

class MembershipController extends BaseController
{
    protected MembershipModel $membershipModel;
    protected MemberModel $memberModel;
    protected MembershipPlanModel $planModel;

    public function __construct()
    {
        $this->membershipModel = new MembershipModel();
        $this->memberModel     = new MemberModel();
        $this->planModel       = new MembershipPlanModel();
    }

    /**
     * CREATE MEMBERSHIP
     * - support stacking
     * - support future start
     * - prevent overlap
     */
    public function store()
    {
        /** -------------------------------------------------
         * VALIDATION (basic payload)
         * -------------------------------------------------*/
        $rules = [
            'member_id'          => 'required|integer',
            'membership_plan_id' => 'required|integer',
            'start_date'         => 'required|valid_date',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $memberId     = (int) $this->request->getPost('member_id');
        $planId       = (int) $this->request->getPost('membership_plan_id');
        $inputStart   = $this->request->getPost('start_date');
        $today        = date('Y-m-d');

        /** -------------------------------------------------
         * MEMBER VALIDATION
         * -------------------------------------------------*/
        $member = $this->memberModel->find($memberId);

        if (! $member || ! $member['is_active']) {
            return redirect()->back()
                ->with('error', 'Invalid or inactive member');
        }

        /** -------------------------------------------------
         * PLAN VALIDATION
         * -------------------------------------------------*/
        $plan = $this->planModel->getValidPlan($planId);

        if (! $plan) {
            return redirect()->back()
                ->with('error', 'Invalid or inactive membership plan');
        }

        // target member type check
        if (! $this->planModel->isAllowedForMember($plan, $member['member_type'])) {
            return redirect()->back()
                ->with('error', 'This plan is not allowed for this member type');
        }

        /** -------------------------------------------------
         * STACKING LOGIC (CORE RULE)
         * -------------------------------------------------*/
        $activeMembership = $this->membershipModel
            ->getActiveMembership($memberId);

        if ($activeMembership) {
            // 🔵 STACKING MODE
            // start otomatis setelah end membership aktif
            $startDate = date(
                'Y-m-d',
                strtotime('+1 day', strtotime($activeMembership['end_date']))
            );

            $status = 'REGISTERED';
        } else {
            // 🟢 NORMAL MODE
            // start date bebas (today / future), tapi tidak boleh ke masa lalu
            if ($inputStart < $today) {
                return redirect()->back()
                    ->with('error', 'Start date cannot be in the past');
            }

            $startDate = $inputStart;
            $status    = ($startDate > $today) ? 'REGISTERED' : 'ACTIVE';
        }

        /** -------------------------------------------------
         * CALCULATE END DATE
         * -------------------------------------------------*/
        $endDate = date(
            'Y-m-d',
            strtotime("+{$plan['duration_days']} days", strtotime($startDate))
        );

        /** -------------------------------------------------
         * INSERT (SNAPSHOT SAFE)
         * -------------------------------------------------*/
        $this->membershipModel->insert([
            'member_id'              => $memberId,
            'membership_plan_id'     => $planId,

            // snapshot (harga & nama aman walau plan berubah)
            'plan_name_snapshot'     => $plan['name'],
            'price_snapshot'         => $plan['price'],
            'daily_limit'            => $plan['daily_limit'],
            'status'                 => $status,
            'start_date'             => $startDate,
            'end_date'               => $endDate,
        ]);

        return redirect()
            ->to('/admin/member')
            ->with('success', 'Membership created successfully');
    }

    /**
     * SUSPEND MEMBERSHIP (manual admin)
     */
    public function suspend(int $id)
    {
        $membership = $this->membershipModel->find($id);

        if (! $membership) {
            return redirect()->back()->with('error', 'Membership not found');
        }

        $this->membershipModel->update($id, [
            'status' => 'SUSPENDED',
        ]);

        return redirect()->back()
            ->with('success', 'Membership suspended');
    }

    /**
     * ACTIVATE / RESUME MEMBERSHIP (manual)
     */
    public function activate(int $id)
    {
        $membership = $this->membershipModel->find($id);

        if (! $membership) {
            return redirect()->back()->with('error', 'Membership not found');
        }

        // jangan aktifkan jika start date masih di masa depan
        if ($membership['start_date'] > date('Y-m-d')) {
            return redirect()->back()
                ->with('error', 'Membership has not started yet');
        }

        $this->membershipModel->update($id, [
            'status' => 'ACTIVE',
        ]);

        return redirect()->back()
            ->with('success', 'Membership activated');
    }
}
