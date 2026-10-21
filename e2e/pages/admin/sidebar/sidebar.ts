import { APIRequestContext, Page } from '@playwright/test';
import { LocatorType } from '../../../types/locator';
import { BasePage } from '#pages/base.page.js';

export class Sidebar extends BasePage {
  private readonly dashboard: LocatorType;
  private readonly checkin: LocatorType;

  private readonly payment: LocatorType;
  private readonly paymentModal: string;

  private readonly transactions: LocatorType;

  private readonly requests: LocatorType;
  private readonly requestsModal: string;

  private readonly memberData: LocatorType;
  private readonly memberDataModal: string;

  private readonly plans: LocatorType;
  private readonly plansModal: string;

  private readonly message: LocatorType;
  private readonly basicInformation: LocatorType;
  private readonly about: LocatorType;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.dashboard = { description: 'Dashboard menu on sidebar', value: 'dashboard-menu' };
    this.checkin = { description: 'Check In menu on sidebar', value: 'checkin-menu' };

    this.payment = { description: 'Payment menu on sidebar', value: 'payment-menu' };
    this.paymentModal = 'adminCasualPassModal';

    this.transactions = { description: 'Transactions menu on sidebar', value: 'transaction-menu' };

    this.requests = { description: 'Membership Request menu on sidebar', value: 'request-menu' };
    this.requestsModal = 'approveRequestModal';

    this.memberData = { description: 'Membership data menu on sidebar', value: 'data-menu' };
    this.memberDataModal = 'viewMembershipModal';

    this.plans = { description: 'Membership plans menu on sidebar', value: 'plans-menu' };
    this.plansModal = 'addPlanModal';

    this.message = { description: 'Contact Message menu on sidebar', value: 'contact-menu' };
    this.basicInformation = {
      description: 'Basic Information menu on sidebar',
      value: 'basicinformation-menu',
    };
    this.about = { description: 'About menu on sidebar', value: 'about-menu' };
  }

  private async waitForPageReady(modalId: string): Promise<void> {
    await this.page.locator(`#${modalId}`).waitFor({ state: 'attached', timeout: 10000 });

    await this.page.waitForFunction(
      () => {
        return typeof (window as any).bootstrap !== 'undefined';
      },
      { timeout: 10000 },
    );
  }

  public async clickDashboardMenuOnSidebar(): Promise<void> {
    return await this.elements.clickElement(this.dashboard);
  }

  public async clickCheckinMenuOnSidebar(): Promise<void> {
    return await this.elements.clickElement(this.checkin);
  }

  public async clickPaymentMenuOnSidebar(): Promise<void> {
    await this.elements.clickElement(this.payment);
    await this.waitForPageReady(this.paymentModal);
  }

  public async clickTransactionsMenuOnSidebar(): Promise<void> {
    return await this.elements.clickElement(this.transactions);
  }

  public async clickMembershipPlanMenuOnSideBar(): Promise<void> {
    await this.elements.clickElement(this.plans);
    await this.waitForPageReady(this.plansModal);
  }

  public async clickMembershipRequestsMenuOnSidebar(): Promise<void> {
    await this.elements.clickElement(this.requests);
    await this.waitForPageReady(this.requestsModal);
  }

  public async clickMemberDataMenuOnSidebar(): Promise<void> {
    await this.elements.clickElement(this.memberData);
    await this.waitForPageReady(this.memberDataModal);
  }
}
