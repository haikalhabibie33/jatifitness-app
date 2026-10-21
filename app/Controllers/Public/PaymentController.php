<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;

use App\Models\MembershipRequestModel;
use App\Models\MemberModel;

use App\Services\Access\AccessService;

class PaymentController extends BaseController
{
    public function start()
    {
        $requestModel = new MembershipRequestModel();
        $memberModel  = new MemberModel();

        $token = $this->request->getGet('token');

        $request = $requestModel
            ->where('access_token', $token)
            ->first();

        if (!$request) {
            return redirect()->to('/')
                ->with('error', 'Invalid payment request.');
        }

        if (in_array($request['status'], ['PAID', 'COMPLETED'], true)) {
            return redirect()
                ->to('/membership/request-submitted?token=' . $request['access_token'])
                ->with('error', 'This request has already been processed.');
        }

        if (!empty($request['approval_required']) && $request['status'] !== 'APPROVED') {
            return redirect()
                ->to('/membership/request-submitted?token=' . $request['access_token'])
                ->with('error', 'This request is pending admin approval.');
        }

        if (empty($request['approval_required']) && $request['status'] !== 'SUBMITTED') {
            return redirect()
                ->to('/membership/request-submitted?token=' . $request['access_token'])
                ->with('error', 'This request is not ready for payment.');
        }

        // duplication check
        if ($request['request_type'] === 'MEMBER') {
            $existingMember = $memberModel
                ->groupStart()
                    ->where('email', $request['email'])
                    ->orWhere('phone', $request['phone'])
                ->groupEnd()
                ->where('deleted_at', null)
                ->first();

            if ($existingMember) {
                return redirect()
                    ->to('/membership/request-submitted?token=' . $request['access_token'])
                    ->with('error', 'Member already exists.');
            }
        }

        return view('pages/web/payment/request', [
            'request' => $request,
            'type'    => strtolower($request['request_type']),
        ]);
    }

    public function complete()
    {
        $token = $this->request->getGet('token');

        if (!$token) {
            return redirect()->to('/');
        }

        $service = new AccessService();

        $data = $service->getByToken($token);

        if (!$data) {
            return redirect()->to('/');
        }

        return view('pages/web/access/access_qr', $data);
    }
}