<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\DTO\MembershipPlan\CreatePlanDto;
use App\DTO\MembershipPlan\UpdatePlanDto;
use App\Models\MembershipPlanModel;
use App\Services\MembershipPlanService;
use App\Traits\RequestValidationTrait;
use App\DTO\MembershipPlan\PagerViewDto;

class MembershipPlanController extends BaseController
{
    protected MembershipPlanModel $planModel;
    private MembershipPlanService $membershipPlanService;

    use RequestValidationTrait;

    public function __construct()
    {
        $this->planModel = new MembershipPlanModel();

        $this->membershipPlanService = new MembershipPlanService();
    }

    private const PAGER_RULES = [
        'search' => 'permit_empty|max_length[100]|regex_match[/^[a-zA-Z0-9\s@._+\-()]+$/]',
        'target' => 'permit_empty|in_list[All,REGULAR,STUDENT]',
        'scope' => 'permit_empty|in_list[All,MEMBER_ONLY,CASUAL_ONLY,BOTH]',
        'status' => 'permit_empty|in_list[All,ACTIVE,INACTIVE]',
    ];

    public function index()
    {
        // $plans = $this->planModel->findAll();
        $query = $this->request->getGet();
        if (!$this->validate(self::PAGER_RULES, $query)) {
            return redirect()
                ->back()
                ->with('error', 'invalid request');
        }
        $dto = PagerViewDto::fromGet($query);

        $result = $this->membershipPlanService->findAll($dto);
        // $plans = [];
        // if (!$result['success']) {
        //     $plans = [];
        // }
        $data = $result['data'];
        // dd($data);
        // $plans = $result['data']['plans'];

        return view('pages/admin/membership_plans/index', [
            'active' => 'membershipPackage',
            'plans'  => $data['plans'],
            'pager' => $data['pager'],
            'limit' => $dto->limit,
            'page' => $dto->page,
        ]);
    }

    public function store()
    {
        $dto = CreatePlanDto::fromPost($this->request->getPost());
        $result = $this->membershipPlanService->create($dto);
        if (!$result['success']) {
            return redirect()
                ->back()
                ->with('error', $result['error']);
        }

        return redirect()
        ->back()
        ->with('success', 'Membership plan created');
    }

    public function update(int $id)
    {
        $dto = UpdatePlanDto::fromPost($this->request->getPost());
        $result = $this->membershipPlanService->update($id, $dto);
        if (!$result['success']) {
            return redirect()
                ->back()
                ->with('error', $result['error']);
        }

        return redirect()
            ->back()
            ->with('success', 'Membership plan updated');
    }

    public function toggle(int $id)
    {
        $plan = $this->planModel->find($id);

        if (! $plan) {
            return redirect()->back()->with('error', 'Plan not found');
        }

        $this->planModel->update($id, [
            'is_active' => ! $plan['is_active'],
        ]);

        return redirect()->back()->with('success', 'Plan status updated');
    }
}