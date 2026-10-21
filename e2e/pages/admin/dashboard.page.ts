import { APIRequestContext, Page } from '@playwright/test';
import { LocatorType } from '../../types/locator';
import { BasePage } from '#pages/base.page.js';

export default class DashboardPage extends BasePage {
  private readonly activeMembers: LocatorType;
  private readonly newMembers: LocatorType;
  private readonly inactiveMembers: LocatorType;
  private readonly totalMembers: LocatorType;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.activeMembers = {
      description: 'Card shows active members number',
      value: 'active-members',
    };
    this.newMembers = { description: 'Card shows new members number', value: 'new-members' };
    this.inactiveMembers = {
      description: 'Card shows inactive members number',
      value: 'inactive-members',
    };
    this.totalMembers = { description: 'Card shows total members number', value: 'total-members' };
  }

  public async expectMemberCardsVisible(): Promise<void> {
    await this.assertion.elementVisible(this.activeMembers);
    await this.assertion.elementVisible(this.newMembers);
    await this.assertion.elementVisible(this.inactiveMembers);
    await this.assertion.elementVisible(this.totalMembers);
  }
}
