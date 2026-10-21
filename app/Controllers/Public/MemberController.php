<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\DTO\MembershipRequest\SubmitCasualDTO;
use App\DTO\MembershipRequest\SubmitMemberDto;
use App\Models\MembershipRequestModel;
use App\Models\PageSettingsModel;

use App\Helpers\RequestStatusHelper;
use App\Services\MemberService;
use App\Services\MembershipPlanService;
use App\Services\MembershipRequestService;

class MemberController extends BaseController
{
    
    private MembershipRequestService $membershipRequestService;
    private MembershipPlanService $membershipPlanService;
    private MemberService $memberService;

    public function __construct()
    {
        $this->memberService = new MemberService();
        $this->membershipRequestService = new MembershipRequestService();
        $this->membershipPlanService = new MembershipPlanService();
    }

  public function index(): string
  {
    $casualPlanList = $this->membershipPlanService->findAllCasualActivePlans();
    $casualPlans = [];
    if (!$casualPlanList['success']) {
        $casualPlans = [];
    }
    $casualPlans = $casualPlanList['data'];

    $memberPlanList = $this->membershipPlanService->findAllMemberActivePlans();
    $memberPlans = [];
    if (!$memberPlanList['success']) {
        $memberPlans = [];
    }
    $memberPlans = $memberPlanList['data'];

    return view('pages/web/membership/index',[
        'casualPlans' => $casualPlans,
        'memberPlans' => $memberPlans,
    ]);
  }

  private function isOnlinePaymentEnabled(): bool
    {
        $ps = new PageSettingsModel();
        return $ps->getBool('system', 'online_payment_enabled', true);
    }

    
  public function submitCasual()
    {
      $rules = [
        'target_type' => 'required|in_list[REGULAR,STUDENT]',
        'membership_plan' => 'required|integer',
        'quantity'        => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[30]',
        'name'            => 'required|max_length[100]',
        'phone'           => 'required|max_length[20]',
        'email'           => 'required|valid_email|max_length[100]',
        'gender'          => 'required|in_list[MALE,FEMALE]',
        'payment_method'  => 'required|in_list[CASH,TRANSFER]',
      ];

      if (!$this->validate($rules)) {
          return redirect()->back()
              ->withInput()
              ->with('errors', $this->validator->getErrors());
      }

    $dto = SubmitCasualDTO::fromPost($this->request->getPost());
    $results = $this->membershipPlanService->validateCasualInputToMembershipPlan($dto);

      if (!$results['ok']) {
          return redirect()->back()
              ->with('errors', $results['error']);
      }

      // insert to membership request
      $result = $this->membershipRequestService->submitCasual($dto);

      if (!$result['ok']) {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', $result['error']);
      }

      if ($this->isOnlinePaymentEnabled()) {
          return redirect()
              ->to('/payment/start?token=' . $result['accessToken'])
              ->with('success', 'Please complete your payment to activate access.');
      } 
     return redirect()
        ->to('/membership/request-submitted?token=' . $result['accessToken'])
        ->with('info', 'Please proceed to admin desk for payment');

    
  }

  public function checkMemberAvailability()
    {
        $inputData = [
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
        ];
        if (!$inputData['phone'] || !$inputData['email']) {
            return [
                'ok' => false, 
                'message' => 'Invalid input',
                'debug' => null
                ];
        }

        $results = $this->memberService->isMemberAvailable($inputData);

        return $this->response->setJSON([
            'ok' => $results['ok'],
            'debug' => $results['debug'],
            'message' => $results['message']
        ]);
    }
    
  public function submitMembership()
    {
        log_message('debug', '[submitMembership] HIT');
        $rules = [
            'target_type'     => 'required|in_list[REGULAR,STUDENT]',
            'membership_plan' => 'required|integer',
            'name'            => 'required|max_length[100]',
            'gender'          => 'required|in_list[MALE,FEMALE]',
            'date_of_birth'   => 'required|valid_date',
            'phone'           => 'required|max_length[30]',
            'email'           => 'required|valid_email|max_length[100]',
            'address'         => 'required|max_length[255]',
            'payment_method'  => 'required|in_list[CASH,TRANSFER,QRIS]',
        ];

        if (!$this->validate($rules)) {
            return redirect()
                    ->back()
                    ->with('errors', $this->validator->getErrors());
        }

        $dto = SubmitMemberDto::fromPost($this->request->getPost());

        $results = $this->membershipPlanService->validateMemberInputToMembershipPlan($dto);

        if (!$results['ok']) {
            return redirect()
              ->back()
              ->with('errors', $results['error']);
        }

        $result = $this->membershipRequestService->submitMember($dto);
        if (!$result['ok']) {
            return redirect()
                ->back()
                ->with('error', $result['error']);
        }

        if ($this->isOnlinePaymentEnabled()) {
            return redirect()
            ->to('/payment/start?token=' . $result['accessToken']);
        } 
        // processing to offline payment
        return redirect()
        ->to('/membership/request-submitted?token=' . $result['accessToken'])
        ->with('info','Please proceed to admin desk for payment');
        

    }

  public function requestSubmitted()
  {  
    $pageSettingsModel = new PageSettingsModel();
    $contact = $pageSettingsModel->getPage('contact');

    $token = $this->request->getGet('token');

    if (!$token) {
        return redirect()->to('/');
    }

    $model = new MembershipRequestModel();
    $request = $model->where('access_token', $token)->first();

    if (!$request) {
        return redirect()->to('/');
    }

    $helper = new RequestStatusHelper(
        $request,
        $contact['contact_phone']);

    return view('pages/web/membership/request_submitted',[
        'request' => $request,
        'helper' => $helper,
    ]);
  }
}
