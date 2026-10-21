<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Services\MemberService;
use App\Services\MembershipPlanService;
use App\Services\MembershipService;

class MemberController extends BaseController
{
    private MemberService $memberService;
    private MembershipService $membershipService;
    private MembershipPlanService $membershipPlanService;

    public function __construct()
    {

        $this->memberService = new MemberService();
        $this->membershipService = new MembershipService();
        $this->membershipPlanService = new MembershipPlanService();
    }

    public function index()
    {
        $membersResult = $this->membershipService->findAllMembershipOfMember();
        // dd($membersResult);
        $members = $membersResult['ok']
            ? $membersResult['data']
            : [];


        // $plans = $this->planModel->getActivePlans();
        $planResult = $this->membershipPlanService->findAllActivePlans();
        $plans = $planResult['success']
            ? $planResult['data']
            : [];


        return view('pages/admin/member_data/index', [
            'members' => $members,
            'plans'   => $plans,
            'active'  => 'member',
        ]);
    }

    public function store()
    {
        $rules = $this->memberService->getMemberRules();

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userInput = [
            'name'          => $this->request->getPost('name'),
            'gender'        => $this->request->getPost('gender'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'phone'         => $this->request->getPost('phone'),
            'email'         => $this->request->getPost('email'),
            'address'       => $this->request->getPost('address'),
            'member_type'   => $this->request->getPost('member_type'),
            'is_active'     => $this->request->getPost('is_active') ? true : false,
        ];

        $result = $this->memberService->addNewMember($userInput);
        // dd($result);
        if (!$result['success']) {
            return redirect()
                ->back()
                ->with('error', $result['message']);
        }

        return redirect()
                ->back()
                ->with('success', 'successfully create member');
    }

    /**
     * Update member identity
     */
    public function update(int $id)
    {
        $rules = $this->memberService->getMemberRules();
        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userInput = [
            'id'            => $id,
            'name'          => $this->request->getPost('name'),
            'gender'        => $this->request->getPost('gender'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'phone'         => $this->request->getPost('phone'),
            'email'         => $this->request->getPost('email'),
            'address'       => $this->request->getPost('address'),
            'member_type'   => $this->request->getPost('member_type'),
            'is_active'     => $this->request->getPost('is_active') ? true : false,
        ];
        $result = $this->memberService->updateMemberData($userInput);
        return redirect()
            ->back()
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    /**
     * Deactivate member (SOFT DELETE)
     */
    public function deactivate(int $id)
    {
        $result = $this->memberService->deactivateMember($id);

        return redirect()
            ->back()
            ->with($result['success']? 'success' : 'error', $result['message']);
    }

    public function addMembership(int $id)
    {
        // $memberData = $this->memberModel->find($id);
        $member = $this->memberService->findMemberByID($id);
        if (!$member['success']) {
            return redirect()
            ->back()
            ->with('error', 'Member is not found!');
        }

        $memberData = $member['data'];
        $userInput = [
            'id'                => $id,
            'plan_id'           => $this->request->getPost('membership_plan_id'),
            'payment_method'    => $this->request->getPost('payment_method'),
            'name'              => $memberData['name'],
            'gender'            => $memberData['gender'],
            'email'             => $memberData['email'],
            'phone'             => $memberData['phone'],
            'date_of_birth'     => $memberData['date_of_birth'],
            'address'           => $memberData['address'],
            'target_type'       => $memberData['member_type']
        ];

        $result = $this->memberService->addMembershipToMember($userInput);
        return redirect()
                ->back()
                ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}