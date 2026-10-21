<?php 

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\Transactions\TransactionReportService;
use App\DTO\Transaction\ViewDTO;
use App\Traits\RequestValidationTrait;

class TransactionController extends BaseController
{
    private TransactionReportService $transactionReportService;
    use RequestValidationTrait;

    public function __construct()
    {
        $this->transactionReportService = new TransactionReportService();
    }

    private const RULES = [
        'search' => 'permit_empty|max_length[100]|regex_match[/^[a-zA-Z0-9\s@._+\-()]+$/]',
        'method' => 'permit_empty|in_list[All,CASH,QRIS,TRANSFER]',
        'status' => 'permit_empty|in_list[All,SUCCESS,FAILED,REFUNDED]',
        'from' => 'permit_empty|valid_date[Y-m-d]',
        'to' => 'permit_empty|valid_date[Y-m-d]',
        ];
    
    public function index()
    {
        $query = $this->request->getGet();
        if (!$this->validate(self::RULES, $query)){
            return redirect()
                ->back()
                ->with('error', 'invalid body request!');
        }
        $dto = ViewDTO::fromGet($query);
        $result = $this->transactionReportService->view($dto);
        $data = $result['data'];

        if (!$result['ok']) {
            return redirect()
                ->back()
                ->with('error', $result['error']);
        }

        // ========================
        // RETURN VIEW
        // ========================
        return view('pages/admin/transaction', [
            'transactions' => $data['transactions'],
            'pager'        => $data['pager'],
            'limit'        => $dto->limit,
            'page'         => $dto->page,
            'active'       => 'transaction',
        ]);
    }

    // testing only---------
    // public function fakeInsert()
    // {
    //     $db = \Config\Database::connect();

    //     $transactionModel = new TransactionModel();
    //     $requestModel     = new MembershipRequestModel();
    //     $planModel        = new MembershipPlanModel();

    //     $methods  = ['CASH', 'QRIS', 'TRANSFER'];
    //     $statuses = ['SUCCESS', 'FAILED'];

    //     $plans = $planModel->findAll();
    //     if (empty($plans)) {
    //         return $this->response->setJSON(['error' => 'No plans']);
    //     }

    //     $plan = $plans[array_rand($plans)];
    //     $db->transStart();

    //     // ========================
    //     // INSERT REQUEST
    //     // ========================
    //     $requestData = [
    //         'name'        => 'User ' . rand(1000, 9999),
    //         'phone'       => '08' . rand(1000000000, 9999999999),
    //         'email'       => 'user' . rand(1000, 9999) . '@mail.com',
    //         'member_type' => $plan['target_member_type'],
    //         'status'      => 'APPROVED',
    //         'access_token' => bin2hex(random_bytes(16)),
    //         'created_at'  => date('Y-m-d H:i:s'),
    //     ];

    //     if (!$requestModel->insert($requestData)) {
    //         return $this->response->setJSON([
    //             'error' => 'request insert failed',
    //             'detail' => $requestModel->errors()
    //         ]);
    //     }

    //     $requestId = $requestModel->insertID(); // ✅ AMAN

    //     // ========================
    //     // BUILD TRANSACTION
    //     // ========================
    //     $purchaseType = rand(0, 1) ? 'MEMBER' : 'CASUAL';

    //     if ($purchaseType === 'CASUAL') {

    //         $price = rand(20000, 40000);
    //         $qty   = rand(1, 5);

    //         $data = [
    //             'membership_request_id' => $requestId,
    //             'purchase_type' => 'CASUAL',

    //             'target_member_type_snapshot' => $plan['target_member_type'],

    //             'plan_code_snapshot' => 'CASUAL',
    //             'plan_name_snapshot' => 'Daily Visit',
    //             'price_snapshot' => $price,
    //             'duration_days_snapshot' => 1,

    //             'quantity' => $qty,
    //             'total_amount' => $price * $qty,
    //         ];

    //     } else {

    //         $data = [
    //             'membership_request_id' => $requestId,
    //             'purchase_type' => 'MEMBER',

    //             'target_member_type_snapshot' => $plan['target_member_type'],

    //             'plan_code_snapshot' => $plan['plan_code'],
    //             'plan_name_snapshot' => $plan['name'],
    //             'price_snapshot' => $plan['price'],
    //             'duration_days_snapshot' => $plan['duration_days'],

    //             'quantity' => 1,
    //             'total_amount' => $plan['price'],
    //         ];
    //     }

    //     // ========================
    //     // FINAL DATA
    //     // ========================
    //     $data['payment_method'] = $methods[array_rand($methods)];
    //     $data['payment_status'] = $statuses[array_rand($statuses)];
    //     $data['paid_at']        = date('Y-m-d H:i:s', strtotime('-' . rand(0, 60) . ' days'));
    //     $data['created_at']     = date('Y-m-d H:i:s');
        
    //     // ========================
    //     // INSERT TRANSACTION
    //     // ========================
    //     if (!$transactionModel->insert($data)) {
    //         return $this->response->setJSON([
    //             'error' => 'transaction insert failed',
    //             'detail' => $transactionModel->errors()
    //         ]);
    //     }
    //     $db->transComplete();
        
    //     // ✅ CEK FINAL
    //     if ($db->transStatus() === false) {
    //         return $this->response->setJSON([
    //             'error' => 'Transaction failed'
    //         ]);
    //     }

    //     return $this->response->setJSON([
    //         'status' => 'ok'
    //     ]);
    // }
    // inactive this after load testing done
}
