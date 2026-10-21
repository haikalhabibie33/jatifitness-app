<?php

namespace App\Services;

use App\Contracts\Repositories\MemberRepositoryInterface;
use App\Contracts\Repositories\MembershipRepositoryInterface;
use App\Repositories\MemberRepository;
use App\Repositories\MembershipRepository;

use App\Traits\RepositoryResponseTrait;

class MembershipService
{
    use RepositoryResponseTrait;
    
    private MembershipRepositoryInterface $membershipRepository;
    private MemberRepositoryInterface $memberRepository;

    public function __construct(
        ?MembershipRepositoryInterface $membershipRepository = null,
        ?MemberRepositoryInterface $memberRepository = null
    )
    {
        $this->membershipRepository = $membershipRepository ?? new MembershipRepository();
        $this->memberRepository = $memberRepository ?? new MemberRepository();
    }

    public function findAllMembershipOfMember(): array
    {
        $allMembers = $this->memberRepository->findAll();
        if (!$allMembers['ok']) {
            return [
                'ok' => false,
                'error' => $allMembers['error'],
            ];
        }

        $members = $allMembers['data'];
        if (empty($members)) {
            return [
                'ok' => true,
                'data' => [],
                'error' => null,
            ];
        }

        $memberIds = array_column($members, 'id');
        $allMemberships = $this->membershipRepository->findMembershipsByMemberIds($memberIds);

        if (!$allMemberships['ok']) {
            return [
                'ok' => false,
                'data' => [],
                'error' => $allMemberships['error'],
            ];
        }

        $membershipsByMemberId = [];

        foreach ($allMemberships['data'] as $membership) {
            $memberId = $membership['member_id'];
            $membershipsByMemberId[$memberId][] = $membership;
        }

        foreach ($members as &$member) {
            $memberId = $member['id'];
            $member['membership'] = $membershipsByMemberId[$memberId] ?? [];
        }

        unset($member);

        return [
            'ok' => true,
            'data' => $members,
            'error' => null,
        ];
    }    

}