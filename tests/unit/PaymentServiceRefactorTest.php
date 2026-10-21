<?php

use CodeIgniter\Test\CIUnitTestCase;
use App\Services\User\PaymentServiceRefactor;
use Tests\Support\Fakes\FakeMembershipRequestRepository;
use Tests\Support\Fakes\FakeMembershipPlanRepository;

final class PaymentServiceRefactorTest extends CIUnitTestCase 
{
    public function testGetMembershipPackagesReturnsCasualAndMemberPackages(): void
    {
        $service = new PaymentServiceRefactor(
            new FakeMembershipRequestRepository(),
            new FakeMembershipPlanRepository()
        );

        $result = $service->getMembershipPackages();

        $this->assertArrayHasKey('casualPasses', $result);
        $this->assertArrayHasKey('memberPasses', $result);

        $this->assertCount(1, $result['casualPasses']);
        $this->assertCount(1, $result['memberPasses']);
    }
}