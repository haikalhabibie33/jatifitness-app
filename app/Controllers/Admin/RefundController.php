<?php 

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\TransactionModel;
use App\Services\Transactions\RefundService;

class RefundController extends BaseController
{
    private TransactionModel $transactionModel;
    private RefundService $refundService;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->refundService = new RefundService();
    }

    public function index()
    {
        $activeTab = $this->request->getGet('tab') ?? 'casual';

        if (!in_array($activeTab, ['casual','member'], true)) {
            $activeTab = 'casual';
        }

        if ($activeTab === 'member') {
            // $transactions = [
            //     'transactions' => [],
            //     'pager' => null
            // ];
            // TODO getEligibleMemberForRefund
            $transactions = $this->refundService->getEligibleMemberForRefund();
        } else {
            $transactions = $this->refundService->getEligibleCasualForRefund();
        }

        return view('pages/admin/refund/index', [
            'active' => 'refund',
            'activeTab' => $activeTab,
            'transactions' => $transactions['transactions'],
            'pager' => $transactions['pager'],
        ]);
    }

    public function refundCasual(int $id)
    {
        $result = $this->refundService->executeCasualRefund($id);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->back()->with('success', $result['message']);
    }

    public function refundMember(int $id)
    {
        $result = $this->refundService->executeMemberRefund($id);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->back()->with('success', $result['message']);
    }

    public function history()
    {
        return view('pages/admin/history_refund/index', [
            'active' => 'historyRefund'
        ]);
    }
}
