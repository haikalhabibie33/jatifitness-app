import { LoginPage } from '../pages/login.page';
import DataProvider from '#utils/data.provider.utils.js';

export const loginFixture = {
  loginPage: async ({ page, request }, use) => {
    const loginPagePo = new LoginPage(page, request);
    const data = DataProvider.loginValidUser();

    await loginPagePo.validLogin(data.username, data.password);

    await use();
  },
};
