import { loginFixture } from '#fixtures/login.fixture.js';
import MembershipDataPage from '#pages/admin/membership/membership-data/membership-data.page.js';
import AddMemberModalPage from '#pages/admin/membership/membership-data/modals/add-member.page.js';
import AddMembershipModalPage from '#pages/admin/membership/membership-data/modals/add-memberships.page.js';
import EditMemberModalPage from '#pages/admin/membership/membership-data/modals/edit-members-details.page.js';
import ViewMemberModalPage from '#pages/admin/membership/membership-data/modals/view-member-details.page.js';
import ViewMembershipModalPage from '#pages/admin/membership/membership-data/modals/view-membership-details/view-membership-details.page.js';
import MembershipRequestPage from '#pages/admin/membership/membership-request/membership-request.page.js';
import ApproveMembershipRequestPage from '#pages/admin/membership/membership-request/modals/approve-request.page.js';
import { Sidebar } from '#pages/admin/sidebar/sidebar.js';
import ConfirmPaymentPage from '#pages/admin/transactions/payment/modals/payment-confirm-modal.page.js';
import PaymentPage from '#pages/admin/transactions/payment/payment.page.js';
import MemberPage from '#pages/web/member/member.page.js';
import { loginFixtureType } from '#types/fixtures.type.js';
import { expectNoCodeIgniterError } from '#utils/codeigniter-error.utils.js';
import {test as base} from '@playwright/test';

const test = base.extend<loginFixtureType>(loginFixture);

test.describe('Member data creation without membership happy paths', ()=>{
    test.beforeEach(async({page,request,loginPage: _loginPage})=>{
        const sidebarPo = new Sidebar(page,request);
        const memberPagePo = new MemberPage(page,request);

        await memberPagePo.clearMemberDataTest();
        await sidebarPo.clickMemberDataMenuOnSidebar();
    });

    test.afterEach(async ({page})=>{
        await expectNoCodeIgniterError(page);
    });

    test('create regular member without membership', async({page,request})=>{
        const membershipDataPo = new MembershipDataPage(page,request);
        const addMemberModalPo = new AddMemberModalPage(page,request);
        const viewMemberDetailsPo = new ViewMemberModalPage(page,request);

        await membershipDataPo.clickAddMemberModalButton();
        await addMemberModalPo.createRegularMember();

        await membershipDataPo.assertIfRegularMemberDataAvailable();

        await membershipDataPo.clickViewMemberOfMember();
        await viewMemberDetailsPo.assertRegularMemberDetails();
    });

    test('create student member without membership', async({page,request})=>{
        const membershipDataPo = new MembershipDataPage(page,request);
        const addMemberModalPo = new AddMemberModalPage(page,request);
        const viewMemberDetailsPo = new ViewMemberModalPage(page,request);

        await membershipDataPo.clickAddMemberModalButton();
        await addMemberModalPo.createStudentMember();

        await membershipDataPo.assertIfStudentMemberDataAvailable();

        await membershipDataPo.clickViewMemberOfMember();
        await viewMemberDetailsPo.assertStudentMemberDetails();
    })
});

test.describe('Create member data then update some fields happy paths', ()=>{
    test.beforeEach(async({page,request,loginPage: _loginPage})=>{
        const sidebarPo = new Sidebar(page,request);
        const memberPagePo = new MemberPage(page,request);
        
        await memberPagePo.clearMemberDataTest();
        await sidebarPo.clickMemberDataMenuOnSidebar();
    });

    test.afterEach(async ({page})=>{
        await expectNoCodeIgniterError(page);
    });

    test('create regular member data, then modify some fields', async({page,request})=>{
        const membershipDataPo = new MembershipDataPage(page,request);
        const addMemberModalPo = new AddMemberModalPage(page,request);
        const editMemberDetailsPo = new EditMemberModalPage(page,request);

        await membershipDataPo.clickAddMemberModalButton();
        await addMemberModalPo.createRegularMember();

        await membershipDataPo.assertIfRegularMemberDataAvailable();

        await membershipDataPo.clickEditMemberofMember();
        await editMemberDetailsPo.modifySomeFields();  
    });
});

test.describe('Create member data then add membership happy paths', ()=>{
    test.beforeEach(async({page,request,loginPage: _loginPage})=>{
        const sidebarPo = new Sidebar(page,request);
        const memberPagePo = new MemberPage(page,request);

        await memberPagePo.clearMemberDataTest();
        await sidebarPo.clickMemberDataMenuOnSidebar();
    });

    test.afterEach(async ({page})=>{
        await expectNoCodeIgniterError(page);
    });

    test('create regular member data, then add one membership', async({page,request})=>{
        const membershipDataPo = new MembershipDataPage(page,request);
        const addMembershipModalPo = new AddMembershipModalPage(page,request);
        const addMemberModalPo = new AddMemberModalPage(page,request);
        // const viewMemberDetailsModalPo = new ViewMemberModalPage(page,request);
        const viewMembershipDetailsModalPo = new ViewMembershipModalPage(page,request);

        const sidebarPo = new Sidebar(page,request);
        const paymentPo = new PaymentPage(page,request);
        const confirmPaymentPo = new ConfirmPaymentPage(page,request);

        await membershipDataPo.clickAddMemberModalButton();
        await addMemberModalPo.createRegularMember();

        await membershipDataPo.assertIfRegularMemberDataAvailable();

        await membershipDataPo.clickAddMembershipOfMember();
        await addMembershipModalPo.addRegularMembership();
        // after creat member and add membership, proceed to payment

        await sidebarPo.clickPaymentMenuOnSidebar();
        await paymentPo.inputPhoneAndConfirm();
        // await paymentPo.confirmPayment();

        await confirmPaymentPo.clickYesButton();

        // navigate back to member data
        await sidebarPo.clickMemberDataMenuOnSidebar();
        await membershipDataPo.assertIfRegularMemberDataAvailable();
        await membershipDataPo.clickViewMembershipOfMember();
        await viewMembershipDetailsModalPo.assertRowDataMonthly();
    });

    test('create student member data, then add one membership', async({page,request})=>{
        const membershipDataPo = new MembershipDataPage(page,request);
        const addMembershipModalPo = new AddMembershipModalPage(page,request);
        const addMemberModalPo = new AddMemberModalPage(page,request);
        const viewMembershipDetailsModalPo = new ViewMembershipModalPage(page,request);
        
        const sidebarPo = new Sidebar(page,request);

        const membershipRequestPo = new MembershipRequestPage(page,request);
        const approveRequestPo = new ApproveMembershipRequestPage(page,request)

        const paymentPo = new PaymentPage(page,request)
        const confirmPaymentPo = new ConfirmPaymentPage(page,request);

        await membershipDataPo.clickAddMemberModalButton();
        await addMemberModalPo.createStudentMember();

        await membershipDataPo.assertIfStudentMemberDataAvailable();

        await membershipDataPo.clickAddMembershipOfMember();
        await addMembershipModalPo.addStudentMembership();

        // navigate to membership request to approve
        await sidebarPo.clickMembershipRequestsMenuOnSidebar();
        await membershipRequestPo.clickApproveRequest();
        await approveRequestPo.clickApproveButton();
        // navigate to payment to pay
        await sidebarPo.clickPaymentMenuOnSidebar();
        await paymentPo.inputPhoneAndConfirm();
        await confirmPaymentPo.clickYesButton();
        // navigate to member data and open view membership details
        await sidebarPo.clickMemberDataMenuOnSidebar();
        await membershipDataPo.clickViewMembershipOfMember();
        await viewMembershipDetailsModalPo.assertRowDataMonthly();
    });
});