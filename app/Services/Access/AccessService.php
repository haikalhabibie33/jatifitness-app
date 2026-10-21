<?php

namespace App\Services\Access;

use App\Models\MembershipRequestModel;
use App\Models\MembershipModel;
use App\Models\CasualPassModel;
use App\Models\MemberAccessModel;

class AccessService
{
    public function getByToken(string $token): ?array
    {
        $requestModel      = new MembershipRequestModel();
        $membershipModel   = new MembershipModel();
        $casualPassModel   = new CasualPassModel();
        $memberAccessModel = new MemberAccessModel();

        /**
         * ✅ ambil request (PAID + COMPLETED saja)
         */
        $request = $requestModel
            ->where('access_token', $token)
            ->whereIn('status', ['PAID', 'COMPLETED'])
            ->first();

        if (!$request) {
            return null;
        }

        $requestId = $request['id'];
        $accessMode = $request['request_type'];

        /**
         * ✅ CASUAL
         */
        if ($accessMode === 'CASUAL') {

            $access = $casualPassModel
                ->where('membership_request_id', $requestId)
                ->first();

        } else {

            /**
             * ✅ MEMBER
             */
            $membership = $membershipModel
                ->where('membership_request_id', $requestId)
                ->first();

            if (!$membership) {
                return null;
            }

            $access = $memberAccessModel
                ->where('membership_id', $membership['id'])
                ->where('status', 'ACTIVE')
                ->first();
        }

        if (!$access) {
            return null;
        }

        return [
            'request'    => $request,
            'access'     => $access,
            'accessMode' => $accessMode,
        ];
    }
}