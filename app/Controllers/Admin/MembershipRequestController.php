<?php 

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MembershipRequestModel;

class MembershipRequestController extends BaseController
{
    private MembershipRequestModel $membershipRequestModel;

    public function __construct()
    {
        $this->membershipRequestModel = new MembershipRequestModel();
    }

    public function index()
    {
        // $this->membershipRequestModel = new MembershipRequestModel();
        $requests = $this->membershipRequestModel
                    ->where('target_member_type', 'STUDENT')
                    ->orderBy("CASE 
                        WHEN status = 'SUBMITTED' THEN 1
                        ELSE 2 END", 'ASC')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
                    
        return view('pages/admin/membership_requests/index', [
            'active' => 'memberRequest',
            'requests' => $requests
        ]);
    }

    public function approve(int $id)
    {
        // $this->membershipRequestModel = new MembershipRequestModel();
        $request = $this->membershipRequestModel->find($id);

        if (!$request) {
            return redirect()->back()->with('error', 'Membership request not found.');
        }

        if ($request['status'] !== 'SUBMITTED') {
            return redirect()->back()->with('error', 'Only submitted requests can be approved.');
        }

        $this->membershipRequestModel->update($id, ['status' => 'APPROVED']);

        return redirect()->back()->with('success', 'Membership request approved successfully.');
    }

    public function reject(int $id)
    {
        // $this->membershipRequestModel = new MembershipRequestModel();
        $request = $this->membershipRequestModel->find($id);

        if (!$request) {
            return redirect()->back()->with('error', 'Membership request not found.');
        }

        if ($request['status'] !== 'SUBMITTED') {
            return redirect()->back()->with('error', 'Only submitted requests can be rejected.');
        }

        if ($request['target_member_type'] !== 'STUDENT') {
            return redirect()->back()->with('error', 'Only requests targeting STUDENT members can be rejected.');
        }

        $this->membershipRequestModel->update($id, ['status' => 'REJECTED']);

        return redirect()->back()->with('success', 'Membership request rejected successfully.');
    }
}