import {loginFixture} from '#fixtures/login.fixture';
import { loginFixtureType } from '#types/fixtures.type.js';
import {test as base} from '@playwright/test';
import DashboardPage from '#pages/admin/dashboard.page';
import { Sidebar } from '#pages/admin/sidebar/sidebar.js';
import { expectNoCodeIgniterError } from '#utils/codeigniter-error.utils.js';

const test = base.extend<loginFixtureType>(loginFixture)

test.describe('Test using test describe', ()=>{
    test.beforeEach(async({page,request,loginPage: _loginPage})=>{
        const sideBarPo = new Sidebar(page,request);
        await sideBarPo.clickDashboardMenuOnSidebar();
        await expectNoCodeIgniterError(page);
    })
    test('show dashboard cards successfully', async({page, request})=>{
        const dashboardPo = new DashboardPage(page,request);
        await dashboardPo.expectMemberCardsVisible();
        await expectNoCodeIgniterError(page);;
    });

})