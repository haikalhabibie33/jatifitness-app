import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import TableUtils from '#utils/table.utils.js';
import { APIRequestContext, Page } from '@playwright/test';
import { MembershipRequestTableConfig } from './membership-request.table';
import { PersonalDataType } from '#types/personal.type.js';
import DataProvider from '#utils/data.provider.utils.js';

export default class MembershipRequestPage extends BasePage {
  private readonly refreshButton: LocatorType;

  private readonly totalPendingCard: LocatorType;
  private readonly totalApprovedCard: LocatorType;
  private readonly totalRejectedCard: LocatorType;
  private readonly totalMemberCard: LocatorType;
  private readonly totalCasualCard: LocatorType;

  private readonly searchInput: LocatorType;
  private readonly requestTypeSelect: LocatorType;
  private readonly applyFilterButton: LocatorType;

  private readonly table: TableUtils<
    typeof MembershipRequestTableConfig.columns,
    typeof MembershipRequestTableConfig.actions
  >;

  private readonly personalDetails: PersonalDataType;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);

    this.refreshButton = { description: '', value: 'refresh-button' };

    this.totalPendingCard = { description: '', value: 'pending-card' };
    this.totalApprovedCard = { description: '', value: 'approved-card' };
    this.totalRejectedCard = { description: '', value: 'rejected-card' };
    this.totalMemberCard = { description: '', value: 'member-card' };
    this.totalCasualCard = { description: '', value: 'casual-card' };

    this.searchInput = { description: 'Search data in table', value: 'search-input' };
    this.requestTypeSelect = {
      description: 'Select Member or Casual',
      value: 'select-request-type',
    };
    this.applyFilterButton = {
      description: 'To apply search or/and request type',
      value: 'apply-filter',
    };

    this.table = new TableUtils(page, MembershipRequestTableConfig);
    this.personalDetails = DataProvider.getPersonalDetails();
  }

  private async clickRefreshButton(): Promise<void> {
    await this.elements.clickElement(this.refreshButton);
  }

  private async getTotalPending(): Promise<string> {
    return await this.elements.getElementText(this.totalPendingCard);
  }

  private async getTotalApproved(): Promise<string> {
    return await this.elements.getElementText(this.totalApprovedCard);
  }

  private async getTotalRejected(): Promise<string> {
    return await this.elements.getElementText(this.totalRejectedCard);
  }

  private async getTotalMember(): Promise<string> {
    return await this.elements.getElementText(this.totalMemberCard);
  }

  private async getTotalCasual(): Promise<string> {
    return await this.elements.getElementText(this.totalCasualCard);
  }

  private async fillSearchInput(text: string): Promise<void> {
    await this.elements.fillElement(this.searchInput, text);
    await this.assertion.expectToHaveValue(this.searchInput, text);
  }

  private async getAccessToken(): Promise<string> {
    const request = await this.membershipRequestApiUtils.getMembershipRequestIdToken(
      this.personalDetails.phone,
      this.personalDetails.email,
    );
    return request.token;
  }

  private async selectRequestType(member: boolean): Promise<void> {
    const input = member ? 'MEMBER' : 'CASUAL';
    await this.elements.selectByValue(this.requestTypeSelect, input);
    await this.assertion.expectToHaveValue(this.requestTypeSelect, input);
  }

  public async clickDetailRequest(): Promise<void> {
    const token = await this.getAccessToken();
    await this.table.clickAction({ token }, 'detailRequest');
  }

  public async clickApproveRequest(): Promise<void> {
    const token = await this.getAccessToken();
    await this.table.clickAction({ token }, 'approveRequest');
  }

  public async clickRejectRequest(): Promise<void> {
    const token = await this.getAccessToken();
    await this.table.clickAction({ token }, 'rejectRequest');
  }

  public async isDisabledVisible(): Promise<boolean> {
    return !(await this.table.isEmpty());
  }
}
