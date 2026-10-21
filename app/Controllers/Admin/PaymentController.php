<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\DTO\MembershipRequest\SubmitCasualDTO;
use App\DTO\MembershipRequest\SubmitMemberDto;
use App\Models\MembershipPlanModel;
use App\Models\MembershipRequestModel;
use App\Models\PageSettingsModel;

use App\Services\Transactions\PaymentService;
use App\Services\Access\AccessService;
use App\Services\MemberService;
use App\Services\MembershipPlanService;
use App\Services\MembershipRequestService;
use App\Traits\GenerateAccessToken;
use App\Traits\RequestValidationTrait;

class PaymentController extends BaseController
{
    use GenerateAccessToken;    
    use RequestValidationTrait;

    private MembershipPlanModel $membershipPlanModel;
    private MembershipRequestModel $membershipRequestModel;
    private PageSettingsModel $pageSettingsModel;

    private PaymentService $paymentService;

    private MembershipRequestService $membershipRequestService;
    private MembershipPlanService $membershipPlanService;
    private MemberService $memberService;

    public function __construct()
    {
        $this->membershipPlanModel = new MembershipPlanModel();
        $this->membershipRequestModel = new MembershipRequestModel();
        $this->pageSettingsModel = new PageSettingsModel();

        $this->paymentService = new PaymentService();
        $this->memberService = new MemberService();

        $this->membershipRequestService = new MembershipRequestService();
        $this->membershipPlanService = new MembershipPlanService();
    }

    public function index()
    {
        $rules = [
            'input' => 'permit_empty|max_length[32]|regex_match[/^[A-Za-z0-9+\-\s()]+$/]',
            'mode'  => 'permit_empty|in_list[auto,phone,token]'
        ];

        if (!$this->validate($rules,$this->request->getGet())){
            return redirect()
                ->to('admin/payment')
                ->with('error', 'invalid request!');
        }

        $input = trim($this->request->getGet('input') ?? '');
        $rawMode  = trim($this->request->getGet('mode') ?? '');

        $mode = null;
        if ($rawMode === 'auto') {
            if (strlen($input) <= 13) {
                $mode = 'phone';
            } else {
                $mode = 'token';
            }
        } else {
            $mode = $rawMode;
        }
        // dd($mode);
        $searchResults = [];

        // Casual passes handler
            $casualPasses = $this->membershipPlanModel
                ->whereIn('audience_scope', ['BOTH', 'CASUAL_ONLY'])
                ->where('is_active', true)
                ->findAll();
            
            $memberPasses = $this->membershipPlanModel
                ->whereIn('audience_scope', ['BOTH', 'MEMBER_ONLY'])
                ->where('is_active', true)
                ->findAll();

        /**
         * ✅ PRIORITY: PHONE SEARCH
         */
        if ($mode === 'phone' && !empty($input)) {

            $searchResults = $this->membershipRequestModel
                ->like('phone', $input)
                ->groupStart()

                    ->groupStart()
                        ->where('target_member_type', 'REGULAR')
                        ->where('status', 'SUBMITTED')
                    ->groupEnd()

                    ->orGroupStart()
                        ->where('target_member_type', 'STUDENT')
                        ->where('status', 'APPROVED')
                    ->groupEnd()

                    ->orWhereIn('status', ['PAID', 'COMPLETED'])

                ->groupEnd()
                ->orderBy('created_at', 'DESC')
                ->limit(20)
                ->findAll();

            if (!$searchResults) {
                return redirect()
                    ->to('admin/payment')
                    ->with('error', 'Phone number not found in request!');
            }

        /**
         * ✅ TOKEN MODE (SCAN QR)
         */
        } elseif ($mode === 'token')  {

            $item = $this->membershipRequestModel
                ->where('access_token', $input)
                ->first();

            if ($item) {
                $searchResults = [$item];
            } else {
                return redirect()
                    ->to('admin/payment')
                    ->with('error', 'Access token not found in request!');
            }
            
        }
        // dd($searchResults);

        return view('pages/admin/payment/index', [
            'searchResults' => $searchResults,
            'casualPackages'  => $casualPasses,
            'memberPackages' => $memberPasses,
            'active'        => 'payment'
        ]);
    }


    /**
     * ✅ CONFIRM OFFLINE PAYMENT
     */
    public function confirmOffline()
    {
        $token = $this->request->getPost('token');

        if (!$token) {
            return redirect()
                    ->back()
                    ->with('error', 'Invalid request.');
        }

        // $model = $this->membershipRequestModel;

        $request = $this->membershipRequestModel
            ->where('access_token', $token)
            ->first();

        if (!$request) {
            return redirect()
                    ->back()
                    ->with('error', 'Request not found.');
        }

        /**
         * ✅ VALIDATION (ANTI FRAUD)
         */
        $isRegular = $request['target_member_type'] === 'REGULAR';
        $isStudent = $request['target_member_type'] === 'STUDENT';

        $canProcess =
            ($isRegular && $request['status'] === 'SUBMITTED') ||
            ($isStudent && $request['status'] === 'APPROVED');

        if (!$canProcess) {
            return redirect()
                ->to('/admin/payment')
                ->with('info', 'Request not eligible for payment.');
        }

        /**
         * ✅ PROCESS PAYMENT
         */
        // $service = $this->paymentService;

        $result = $this->paymentService->process(
            $request['id'],
            $token,
            'OFFLINE',
        );

        if (!$result['success']) {
            return redirect()
                ->back()
                ->with('error', $result['message']);
        }

        return redirect()
            ->to('/admin/payment?input=' . $token . '&mode=token')
            ->with('success', 'Payment confirmed successfully.');
    }


    /**
     * ✅ ADMIN ACCESS VIEW (WITH RECOVERY FLOW)
     */
    public function access(string $token)
    {
        if (!$token) {
            return redirect()
            ->back()
            ->with('error', 'Invalid token.');
        }

        $requestModel = new MembershipRequestModel();
        $paymentService = new PaymentService();
        $accessService  = new AccessService();

        $request = $requestModel
            ->where('access_token', $token)
            ->first();

        if (!$request) {
            return redirect()->back()->with('error', 'Request not found.');
        }

        // ✅ RECOVERY TANPA REDIRECT
        if ($request['status'] === 'PAID') {

            $paymentService->process(
                $request['id'],
                $token,
                'OFFLINE'
            );

            // reload state
            $request = $requestModel->find($request['id']);
        }

        $data = $accessService->getByToken($token);

        return view('pages/admin/access_qr', $data ?? [
            'request' => $request,
            'access'  => null,
        ]);
    }

     private function isOnlinePaymentEnabled(): bool
    {
        // $ps = new PageSettingsModel();
        return $this->pageSettingsModel->getBool('system', 'online_payment_enabled', true);
    }

    public function createCasualPasses()
    {
        $rules = [
            'target_type'       => 'required|in_list[REGULAR,STUDENT]',
            'membership_plan'   => 'required|integer',
            'quantity'          => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[30]',
            'name'              => 'required|max_length[100]',
            'phone'             => 'required|max_length[20]',
            'email'             => 'required|valid_email|max_length[100]',
            'gender'            => 'required|in_list[MALE,FEMALE]',
            'payment_method'    => 'required|in_list[CASH,TRANSFER,QRIS]'
        ];

        if(!$this->validate($rules)) {
            return redirect()
                ->back()
                ->with('errors', $this->validator->getErrors());
        }

        $dto = SubmitCasualDTO::fromPost($this->request->getPost());
        $results = $this->membershipPlanService->validateCasualInputToMembershipPlan($dto);

        if (!$results['ok']) {
            return redirect()
                ->back()
                ->with('errors', $results['error']);
        }

        // insert to membership request
        $result = $this->membershipRequestService->submitCasual($dto);
        if (!$result['ok']) {
            return redirect()
                    ->back()
                    ->with('error', $result['error']);
        }

        return redirect()
            ->to('/admin/payment?input=' . $result['accessToken'] . '&mode=token');
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

    public function createMemberPasses()
    {
        $rules = [
        'target_type'     => 'required|in_list[REGULAR,STUDENT]',
        'membership_plan' => 'required|integer',
        'name'            => 'required|max_length[100]',
        'gender'          => 'required|in_list[MALE,FEMALE]',
        'date_of_birth'   => 'required|valid_date',
        'phone'           => 'required|max_length[30]',
        'email'           => 'required|valid_email|max_length[100]',
        'address'         => 'required|max_length[255]',
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

        return redirect()
                ->to('/admin/payment?input=' . $result['accessToken'] . '&mode=token');
    }
}
