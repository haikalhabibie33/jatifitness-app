import { APIRequestContext, Page } from '@playwright/test';
import { LocatorType } from '#types/locator';
import { BasePage } from './base.page';
import { WEB_PAGE } from '#utils/enum.utils';

export class LoginPage extends BasePage {
  private readonly usernameField: LocatorType;
  private readonly passwordField: LocatorType;
  private readonly loginButton: LocatorType;
  private readonly rememberMe: LocatorType;
  private readonly forgotPassword: LocatorType;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.usernameField = { description: 'Username field on login page', value: 'username' };
    this.passwordField = { description: 'password field on login page', value: 'password' };
    this.loginButton = { description: 'Login button', value: 'login-btn' };
    this.rememberMe = { description: 'Remember Me checkbox', value: 'remember-me' };
    this.forgotPassword = { description: 'Forgot password link', value: 'forgot-password' };
  }

  public async validLogin(username: string, password: string) {
    await this.page.goto(WEB_PAGE.LOGIN);
    await this.elements.fillElement(this.usernameField, username);
    await this.elements.fillElement(this.passwordField, password);
    await this.elements.clickElement(this.loginButton);
  }
}
