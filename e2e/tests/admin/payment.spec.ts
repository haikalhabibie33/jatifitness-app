import {loginFixture} from '#fixtures/login.fixture';
import { loginFixtureType } from '#types/fixtures.type.js';
import {test as base} from '@playwright/test';
import PaymentPage from '#pages/admin/transactions/payment/payment.page.js';
import { Sidebar } from '#pages/admin/sidebar/sidebar.js';
import MemberPage from '#pages/web/member/member.page.js';
import BuyCasualPassModalPage from '#pages/admin/transactions/payment/modals/payment-casual-modal.page.js';
import MembershipRequestPage from '#pages/admin/membership/membership-request/membership-request.page.js';
import ApproveMembershipRequestPage from '#pages/admin/membership/membership-request/modals/approve-request.page.js';
import BuyMemberPassModalPage from '#pages/admin/transactions/payment/modals/payment-member-modal.page.js';
import ConfirmPaymentPage from '#pages/admin/transactions/payment/modals/payment-confirm-modal.page.js';
import { expectNoCodeIgniterError } from '#utils/codeigniter-error.utils.js';

const test = base.extend<loginFixtureType>(loginFixture);

test.describe('Purchasing casual pass and proceed to payment happy paths', ()=>{
    test.beforeEach(async({page,request,loginPage: _loginPage})=>{
        const sidebarPo = new Sidebar(page,request);
        const memberPo = new MemberPage(page,request);

        await memberPo.clearMemberDataTest();
        await memberPo.clearCasualDataTest();

        await sidebarPo.clickPaymentMenuOnSidebar();
    });

    test.afterEach(async ({page})=>{
        await expectNoCodeIgniterError(page);
    });
    
    test('Get regular casual pass', async ({ page, request }) => {
        const paymentPo = new PaymentPage(page, request);
        const casualModalPo = new BuyCasualPassModalPage(page,request);
        const confirmModalPo = new ConfirmPaymentPage(page,request);

        await paymentPo.clickBuyCasualPassesButton();
        await casualModalPo.createRegularCasualPass();

        await paymentPo.confirmPayment();
        await confirmModalPo.clickYesButton();
    });

    test('Get student casual pass', async({page,request})=>{
        const sidebarPo = new Sidebar(page,request);

        const paymentPo = new PaymentPage(page,request);
        const casualModalPo = new BuyCasualPassModalPage(page,request);
        const confirmModalPo = new ConfirmPaymentPage(page,request);
        
        const membershipRequestPo = new MembershipRequestPage(page,request);
        const approveRequestPo = new ApproveMembershipRequestPage(page,request);

        await paymentPo.clickBuyCasualPassesButton();
        await casualModalPo.createStudentCasualPass();

        await sidebarPo.clickMembershipRequestsMenuOnSidebar();

        await membershipRequestPo.clickApproveRequest();
        await approveRequestPo.clickApproveButton();

        await sidebarPo.clickPaymentMenuOnSidebar();

        await paymentPo.inputPhoneAndConfirm();
        await confirmModalPo.clickYesButton();
    });
});

test.describe('Purchasing member pass and proceed to payment happy paths', ()=>{
    test.beforeEach(async({page,request,loginPage: _loginPage})=>{
        const sidebarPo = new Sidebar(page,request);
        const memberPo = new MemberPage(page,request);

        await memberPo.clearMemberDataTest();
        await memberPo.clearCasualDataTest();

        await sidebarPo.clickPaymentMenuOnSidebar();
    });

    test.afterEach(async ({page})=>{
        await expectNoCodeIgniterError(page);
    });

    test('Get regular member pass', async({page,request})=>{
        const paymentPo = new PaymentPage(page,request);
        const memberPassPo = new BuyMemberPassModalPage(page,request);
        const confirmModalPo = new ConfirmPaymentPage(page,request);

        await paymentPo.clickBuyMemberPassesButton();
        await memberPassPo.createRegularMembership();
        await paymentPo.confirmPayment();
        await confirmModalPo.clickYesButton();
    });

    test('Get student member pass', async({page,request})=>{
        const sidebarPo = new Sidebar(page,request);
        
        const paymentPo = new PaymentPage(page,request);
        const memberPassPo = new BuyMemberPassModalPage(page, request);
        const confirmModalPo = new ConfirmPaymentPage(page,request);

        const membershipRequestPo = new MembershipRequestPage(page,request);
        const approveRequestPo = new ApproveMembershipRequestPage(page,request);

        await paymentPo.clickBuyMemberPassesButton();
        await memberPassPo.createStudentMembership();

        await sidebarPo.clickMembershipRequestsMenuOnSidebar();
        
        await membershipRequestPo.clickApproveRequest();
        await approveRequestPo.clickApproveButton();

        await sidebarPo.clickPaymentMenuOnSidebar();

        await paymentPo.inputPhoneAndConfirm();
        await confirmModalPo.clickYesButton();
    });
});