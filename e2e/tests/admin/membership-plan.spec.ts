import { loginFixture } from "#fixtures/login.fixture";
import MembershipPlanModalPage from "#pages/admin/membership/membership-plan/membership-plan.page.js";
import AddMembershipPlanModalPage from "#pages/admin/membership/membership-plan/modals/add-membership-plan.page.js";
import EditMembershipPlanModalPage from "#pages/admin/membership/membership-plan/modals/edit-membership-plan.page.js";
import { Sidebar } from "#pages/admin/sidebar/sidebar.js";
import { loginFixtureType } from "#types/fixtures.type";
import { expectNoCodeIgniterError } from "#utils/codeigniter-error.utils.js";
import { test as base} from '@playwright/test';

const test = base.extend<loginFixtureType>(loginFixture);

test.describe('Creating membership plan happy paths', ()=>{
    test.beforeEach(async({page,request,loginPage: _loginPage})=>{
        const sidebarPo = new Sidebar(page,request);
        const membershipPlanPo = new MembershipPlanModalPage(page,request);

        await membershipPlanPo.clearMembershipPlanTestData();
        await sidebarPo.clickMembershipPlanMenuOnSideBar();
    });

    test.afterEach(async({page})=>{
        await expectNoCodeIgniterError(page);
    })

    test('regular membership plan', async({page,request})=>{
        const membershipPlanPo = new MembershipPlanModalPage(page,request);
        const addMembershipPlanPo = new AddMembershipPlanModalPage(page,request);

        await membershipPlanPo.clickAddPlan();
        await addMembershipPlanPo.createRegularMembershipPlan();

        await membershipPlanPo.assertNewRegularMemberPlan();
    });

    test('student membership plan', async({page,request})=>{
        const membershipPlanPo = new MembershipPlanModalPage(page,request);
        const addMembershipPlanPo = new AddMembershipPlanModalPage(page,request)

        await membershipPlanPo.clickAddPlan();
        await addMembershipPlanPo.createStudentMembershipPlan();

        await membershipPlanPo.assertNewStudentMemberPlan();
    });

    test('regular casual plan', async({page,request})=>{
        const membershipPlanPo = new MembershipPlanModalPage(page,request);
        const addMembershipPlanPo = new AddMembershipPlanModalPage(page,request);

        await membershipPlanPo.clickAddPlan();
        await addMembershipPlanPo.createRegularCasualPlan();

        await membershipPlanPo.assertNewRegularCasualPlan();
    });

    test('student casual plan', async({page,request})=>{
        const membershipPlanPo = new MembershipPlanModalPage(page,request);
        const addMembershipPlanPo = new AddMembershipPlanModalPage(page,request);

        await membershipPlanPo.clickAddPlan();
        await addMembershipPlanPo.createStudentCasualPlan();

        await membershipPlanPo.assertNewStudentCasualPlan();
    });

    test('regular and student membership plan', async({page,request})=>{
        const membershipPlanPo = new MembershipPlanModalPage(page,request);
        const addMembershipPlanPo = new AddMembershipPlanModalPage(page,request);

        await membershipPlanPo.clickAddPlan();
        await addMembershipPlanPo.createAllMemberPlan();

        await membershipPlanPo.assertNewAllMemberPlan();
    });

    test('regular and student casual plan', async({page,request})=>{
        const membershipPlanPo = new MembershipPlanModalPage(page,request);
        const addMembershipPlanPo = new AddMembershipPlanModalPage(page,request);

        await membershipPlanPo.clickAddPlan();
        await addMembershipPlanPo.createAllCasualPlan();

        await membershipPlanPo.assertNewAllCasualPlan();
    });

    test('one plan for all', async({page,request})=>{
        const membershipPlanPo = new MembershipPlanModalPage(page,request);
        const addMembershipPlanPo = new AddMembershipPlanModalPage(page,request);

        await membershipPlanPo.clickAddPlan();
        await addMembershipPlanPo.createForAllPlan();

        await membershipPlanPo.assertNewForAllPlan();
    });
})

test.describe('Editing membership plan happy paths', ()=>{
    test.beforeEach(async({page,request,loginPage: _loginPage})=>{
        const sidebarPo = new Sidebar(page,request);
        const membershipPlanPo = new MembershipPlanModalPage(page,request);

        await membershipPlanPo.clearMembershipPlanTestData();
        await sidebarPo.clickMembershipPlanMenuOnSideBar();
    });

    test.afterEach(async({page})=>{
        await expectNoCodeIgniterError(page);
    });

    test('create regular membership plan and edit into student', async({page,request})=>{
        const membershipPlanPo = new MembershipPlanModalPage(page,request);
        const editMembershipPlanPo = new EditMembershipPlanModalPage(page,request);

        await membershipPlanPo.createRegularMembershipPlanApi();
        // await page.pause()
        await page.reload(); // temporary bypass
        await membershipPlanPo.assertNewRegularMemberPlan();
        await membershipPlanPo.clickEditPlan();

        await editMembershipPlanPo.editFromRegularIntoStudentMember();
        await membershipPlanPo.assertEditedStudentMemberPlan();
    });

    test('create student membership plan and edit into regular', async({page,request})=>{
        const membershipPlanPo = new MembershipPlanModalPage(page,request);
        const editMembershipPlanPo = new EditMembershipPlanModalPage(page,request);

        await membershipPlanPo.createStudentMembershipPlanApi();
        await page.reload(); // temporary bypass
        await membershipPlanPo.assertNewStudentMemberPlan();
        await membershipPlanPo.clickEditPlan();

        await editMembershipPlanPo.editFromStudentIntoRegularMember();
        await membershipPlanPo.assertEditedRegularMemberPlan();
    });

    test('create regular casual plan and edit into student', async({page,request})=>{
        const membershipPlanPo = new MembershipPlanModalPage(page,request);
        const editMembershipPlanPo = new EditMembershipPlanModalPage(page,request);

        await membershipPlanPo.createRegularCasualPlanApi();
        await page.reload();
        await membershipPlanPo.assertNewRegularCasualPlan();
        await membershipPlanPo.clickEditPlan();

        await editMembershipPlanPo.editFromRegularIntoStudentCasual();
        await membershipPlanPo.assertEditedStudentCasualPlan();
    });

    test('create student casual plan and eedit into regular', async({page,request})=>{
        const membershipPlanPo = new MembershipPlanModalPage(page,request);
        const editMembershipPlanPo = new EditMembershipPlanModalPage(page,request);

        await membershipPlanPo.createStudentCasualPlanApi();
        await page.reload();
        await membershipPlanPo.assertNewStudentCasualPlan();
        await membershipPlanPo.clickEditPlan();

        await editMembershipPlanPo.editFromStudentIntoRegularCasual();
        await membershipPlanPo.assertEditedRegularCasualPlan();
    })
})