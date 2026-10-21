<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\Models\CasualPassModel;
use App\Models\MembershipRequestModel;
use App\Models\MembershipModel;
use App\Models\MemberAccessModel;

class AccessController extends BaseController
{
    public function casual(string $accessToken)
    {
        $passModel = new CasualPassModel();
        $requestModel = new MembershipRequestModel();

        $pass = $passModel->where('access_token', $accessToken)->first();

        if (!$pass) {
            return redirect('/')
                ->with('error', 'Invalid access token.');
        }

        // ambil request untuk info tambahan (plan, duration, dll)
        $request = $requestModel->find($pass['membership_request_id']);

        return view('pages/web/access/access_qr', [
            'pass'    => $pass,
            'request' => $request,
            'accessMode' => 'CASUAL',
        ]);
    }

    public function member(string $accessToken)
    {
        $accessModel  = new MemberAccessModel();
        $requestModel = new MembershipRequestModel();
        $membershipModel = new MembershipModel();

        $access = $accessModel
            ->where('access_token', $accessToken)
            ->where('status', 'ACTIVE')
            ->first();

        if (!$access) {
            return redirect('/')
                ->with('error', 'Invalid access token.');
        }

        // relasi: member_access -> membership -> membership_request
        $membership = $membershipModel->find($access['membership_id']);
        $request    = $requestModel->find($membership['membership_request_id']);

        return view('pages/web/access/access_qr', [
            'access'     => $access,
            'request'    => $request,
            'accessMode' => 'MEMBER',
        ]);
    }
}