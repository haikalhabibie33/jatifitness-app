import AccessQrPage from "#pages/web/member/access-qr.page.js";
import MemberPage from "#pages/web/member/member.page.js";
import SummaryModalPage from "#pages/web/member/modal/summary.modal.page.js";
import RequestSubmittedPage from "#pages/web/member/request-submitted.page.js";
import { expectNoCodeIgniterError } from "#utils/codeigniter-error.utils.js";
import {test} from "@playwright/test";

test.describe('Applying membership happy paths',()=>{
    
    test.beforeEach(async ({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        await memberPagePo.clearMemberDataTest();
        await memberPagePo.clearCasualDataTest();
        await memberPagePo.gotoMemberPage();
    });

    test.afterEach(async ({page})=>{
            await expectNoCodeIgniterError(page);
    });

    test('applying membership as regular successfully',async ({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);
        const requestSubmittedPo = new RequestSubmittedPage(page,request);
        const accessQrPagePo = new AccessQrPage(page,request);

        await memberPagePo.applyForRegularMember();
        await summaryModalPo.assertAndProceedRegularMemberModal();

        await requestSubmittedPo.checkOfflinePaymentReady();

        await summaryModalPo.confirmPaymentByAdmin();
        await summaryModalPo.reloadPage();

        await requestSubmittedPo.checkPaymentCompleted();
        await requestSubmittedPo.clickViewQrAccessButton();
        await accessQrPagePo.checkFinalResultMember();
    });

    

    test('applying membership as student successfully', async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);
        const requestSubmittedPo = new RequestSubmittedPage(page,request);
        const accessQrPagePo = new AccessQrPage(page,request);

        await memberPagePo.applyForStudentMember();
        await summaryModalPo.assertAndProceedStudentMemberModal();

        await requestSubmittedPo.checkStudentWaitingForApproval();

        await summaryModalPo.approveStudentMembershipByAdmin();

        await requestSubmittedPo.reloadPage();
        await requestSubmittedPo.checkOfflinePaymentReady();

        await summaryModalPo.confirmPaymentByAdmin();

        await requestSubmittedPo.reloadPage();
        await requestSubmittedPo.checkPaymentCompleted();
        await requestSubmittedPo.clickViewQrAccessButton();
        await accessQrPagePo.checkFinalResultMember();
    });

});

test.describe('Applying membership as regular on negative flows', ()=>{
    test.beforeEach(async ({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        await memberPagePo.clearMemberDataTest();
        await memberPagePo.clearCasualDataTest();
        await memberPagePo.gotoMemberPage();
    });

    test.afterEach(async ({page})=>{
            await expectNoCodeIgniterError(page);
    });

    test('Applying membership as regular, but phone number and email are exist already', async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);
  
        await memberPagePo.applyForRegularMembershipByApi();
        await summaryModalPo.confirmPaymentByAdmin();
        await memberPagePo.applyForDuplicatePhoneAndEmailRegularMember();

        await summaryModalPo.assertSummaryModalNotVisible();
    });

    test('Applying membership as regular, but phone number is exist already', async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.applyForRegularMembershipByApi();
        await summaryModalPo.confirmPaymentByAdmin();
        await memberPagePo.applyForDuplicatePhoneOnlyRegularMember();

        await summaryModalPo.assertSummaryModalNotVisible();
    });

    test('Applying membership as regular, but email is exist already', async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);
 
        await memberPagePo.applyForRegularMembershipByApi();
        await summaryModalPo.confirmPaymentByAdmin();
        await memberPagePo.applyForDuplicateEmailOnlyRegularMember();

        await summaryModalPo.assertSummaryModalNotVisible();
    });

    test('Applying membership as regular without phone and email', async({page,request})=>{

        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.applyRegularMembershipWithoutPhoneEmail();

        // await memberPagePo.assertIfMemberRequestNotExist();
        await summaryModalPo.assertSummaryModalNotVisible();
    });

    test('Applying membership as regular without phone only', async({page,request})=>{

        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.applyRegularMembershipWithoutPhoneOnly();

        // await memberPagePo.assertIfMemberRequestNotExist();
        await summaryModalPo.assertSummaryModalNotVisible();
    });
    test('Applying membership as regular without email only', async({page,request})=>{

        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.applyRegularMembershipWithoutEmailOnly();

        // await memberPagePo.assertIfMemberRequestNotExist();
        await summaryModalPo.assertSummaryModalNotVisible();
    });

});

test.describe('Applying membership as student on negative flows', ()=>{
    test.beforeEach(async ({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        await memberPagePo.clearMemberDataTest();
        await memberPagePo.clearCasualDataTest();
        await memberPagePo.gotoMemberPage();
    });

    test.afterEach(async ({page})=>{
            await expectNoCodeIgniterError(page);
    });

    test('Applying membership as student, but phone number and email are exist already', async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.applyForStudentMembershipByApi();
        await summaryModalPo.approveStudentMembershipByAdmin();
        await summaryModalPo.confirmPaymentByAdmin();
        await memberPagePo.applyForDuplicatePhoneAndEmailStudentMember();

        await summaryModalPo.assertSummaryModalNotVisible();
    });

    test('Applying membership as student, but phone number is exist already', async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);
        
        await memberPagePo.applyForStudentMembershipByApi();
        await summaryModalPo.approveStudentMembershipByAdmin();
        await summaryModalPo.confirmPaymentByAdmin();
        await memberPagePo.applyForDuplicatePhoneOnlyStudentMember();

        await summaryModalPo.assertSummaryModalNotVisible();
    });

    test('Applying membership as student, but email is exist already', async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.applyForStudentMembershipByApi();
        await summaryModalPo.approveStudentMembershipByAdmin();
        await summaryModalPo.confirmPaymentByAdmin();
        await memberPagePo.applyForDuplicateEmailOnlyStudentMember();

        await summaryModalPo.assertSummaryModalNotVisible();
    });

    test('Applying membership as student without phone and email', async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.applyStudentMembershipWithoutPhoneEmail();

        // await memberPagePo.assertIfMemberRequestNotExist();
        await summaryModalPo.assertSummaryModalNotVisible();
    });
    test('Applying membership as student without phone only', async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.applyStudentMembershipWithoutPhone();

        // await memberPagePo.assertIfMemberRequestNotExist();
        await summaryModalPo.assertSummaryModalNotVisible();
    });
    test('Applying membership as student without email only', async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.applyStudentMembershipWithoutEmail();

        // await memberPagePo.assertIfMemberRequestNotExist();
        await summaryModalPo.assertSummaryModalNotVisible();
    });

});