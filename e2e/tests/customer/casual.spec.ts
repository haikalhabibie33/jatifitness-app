import {test} from "@playwright/test";

import AccessQrPage from "#pages/web/member/access-qr.page.js";
import MemberPage from "#pages/web/member/member.page.js";
import SummaryModalPage from "#pages/web/member/modal/summary.modal.page.js";
import RequestSubmittedPage from "#pages/web/member/request-submitted.page.js";
import { expectNoCodeIgniterError } from "#utils/codeigniter-error.utils.js";

test.describe('Buying Casual Pass happy paths',()=>{
    test.beforeEach(async ({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        await memberPagePo.clearMemberDataTest();
        await memberPagePo.clearCasualDataTest();
        await memberPagePo.gotoMemberPage();
    });

    test.afterEach(async ({page})=>{
        await expectNoCodeIgniterError(page);
    });

    test('Buy casual pass as regular without modifying quantity', async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);
        const requestSubmittedPo = new RequestSubmittedPage(page,request);
        const accessQrPagePo = new AccessQrPage(page,request);

        await memberPagePo.buyRegularCasualPass();
        await summaryModalPo.assertAndProceedRegularCasualModal();

        await requestSubmittedPo.checkOfflinePaymentReady();

        await summaryModalPo.confirmPaymentByAdmin();
        await summaryModalPo.reloadPage();

        await requestSubmittedPo.checkPaymentCompleted();
        await requestSubmittedPo.clickViewQrAccessButton();
        await accessQrPagePo.checkFinalResultCasual();
    });

    test('Buy casual pass as student without modifying quantity', async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);
        const requestSubmittedPo = new RequestSubmittedPage(page,request);
        const accessQrPagePo = new AccessQrPage(page,request);

        await memberPagePo.buyStudentCasualPass();
        await summaryModalPo.assertAndProceedStudentCasualModal();
        await requestSubmittedPo.checkStudentWaitingForApproval();

        await summaryModalPo.approveStudentMembershipByAdmin();

        await requestSubmittedPo.reloadPage();
        await requestSubmittedPo.checkOfflinePaymentReady();

        await summaryModalPo.confirmPaymentByAdmin();
        await summaryModalPo.reloadPage();

        await requestSubmittedPo.checkPaymentCompleted();
        await requestSubmittedPo.clickViewQrAccessButton();
        await accessQrPagePo.checkFinalResultCasual();
    });

    test('Buy casual pass as regular with modifying quantity', async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);
        const requestSubmittedPo = new RequestSubmittedPage(page,request);
        const accessQrPagePo = new AccessQrPage(page,request);
        const quantity: number = 3;

        await memberPagePo.buyRegularCasualPassWithModiyingQuantity(quantity);
        await summaryModalPo.assertAndProceedRegularCasualModal(quantity);

        await requestSubmittedPo.checkOfflinePaymentReady();

        await summaryModalPo.confirmPaymentByAdmin();
        await summaryModalPo.reloadPage();

        await requestSubmittedPo.checkPaymentCompleted();
        await requestSubmittedPo.clickViewQrAccessButton();
        await accessQrPagePo.checkFinalResultCasual();

    });

    test('Buy casual pass as student with modifying quantity', async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);
        const requestSubmittedPo = new RequestSubmittedPage(page,request);
        const accessQrPagePo = new AccessQrPage(page,request);
        const quantity: number = 7;

        await memberPagePo.buyStudentCasualPassWithModifyingQuantity(quantity);
        await summaryModalPo.assertAndProceedStudentCasualModal(quantity);
        await requestSubmittedPo.checkStudentWaitingForApproval();

        await summaryModalPo.approveStudentMembershipByAdmin();

        await requestSubmittedPo.reloadPage();

        await requestSubmittedPo.checkOfflinePaymentReady();

        await summaryModalPo.confirmPaymentByAdmin();
        await summaryModalPo.reloadPage();

        await requestSubmittedPo.checkPaymentCompleted();
        await requestSubmittedPo.clickViewQrAccessButton();
        await accessQrPagePo.checkFinalResultCasual();
    });
});

test.describe('Buying casual pass as regular on negative flows', ()=> {
    test.beforeEach(async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        await memberPagePo.clearMemberDataTest();
        await memberPagePo.clearCasualDataTest();
        await memberPagePo.gotoMemberPage();
    });

    test.afterEach(async ({page})=>{
        await expectNoCodeIgniterError(page);
    });

    test('Buy casual pass with empty package details, but fill all personal details', async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.buyCasualPassWithEmptyPackage();
        await summaryModalPo.assertSummaryModalNotVisible();
    });

    test('Buy casual pass as regular, but empty all personal details',async ({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.buyRegularCasualPassWithEmptyPersonalDetails();
        await summaryModalPo.assertSummaryModalNotVisible();
    });

    test('Buy casual pass as regular, but empty phone and email', async ({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.buyRegularCasualWithEmptyPhoneAndEmail();
        await summaryModalPo.assertSummaryModalNotVisible();
    });

    test('Buy casual pass as regular, but empty phone only',async ({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.buyRegularCasualWithEmptyPhone();
        await summaryModalPo.assertSummaryModalNotVisible();
    });

    test('Buy casual pass as regular, but empty email only',async ({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.buyRegularCasualWithEmptyEmail();
        await summaryModalPo.assertSummaryModalNotVisible();
    }); 
});

test.describe('Buying casual pass as student on negative flows', ()=>{
    test.beforeEach(async({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        await memberPagePo.clearMemberDataTest();
        await memberPagePo.clearCasualDataTest();
        await memberPagePo.gotoMemberPage();
    });

    test.afterEach(async ({page})=>{
        await expectNoCodeIgniterError(page);
    });
    
    test('Buy casual pass with empty package details, but fill all personal details', async ({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.buyCasualPassWithEmptyPackage();
        await summaryModalPo.assertSummaryModalNotVisible();
    });

    test('Buy casual pass as student, but empty all personal details', async ({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.buyStudentCasualPassWithEmptyPersonalDetails();
        await summaryModalPo.assertSummaryModalNotVisible();
    });

    test('Buy casual pass as student, but empty phone and email',async ({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.buyStudentCasualPassWithEmptyPhoneAndEmail();
        await summaryModalPo.assertSummaryModalNotVisible();
    });

    test('Buy casual pass as student, but empty phone only',async ({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.buyStudentCasualPassWithEmptyPhone();
        await summaryModalPo.assertSummaryModalNotVisible();
    });

    test('Buy casual pass as student, but empty email only',async ({page,request})=>{
        const memberPagePo = new MemberPage(page,request);
        const summaryModalPo = new SummaryModalPage(page,request);

        await memberPagePo.buyStudentCasualPassWithEmptyEmail();
        await summaryModalPo.assertSummaryModalNotVisible();
    });
});