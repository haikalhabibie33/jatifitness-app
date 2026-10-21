import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import TableUtils from '#utils/table.utils.js';
import { APIRequestContext, Page } from '@playwright/test';
import { MembershipDataTableConfig, MembershipDataTableUniqueKeys } from './membership-data.table';
import { MembershipFormDataType } from '#types/api-form/apply.membership.type.js';
import DataProvider from '#utils/data.provider.utils.js';

export default class MembershipDataPage extends BasePage {
  private readonly searchMemberField: LocatorType;
  private readonly exportToCsvButton: LocatorType;
  private readonly addMemberModalButton: LocatorType;

  private readonly memberTable: TableUtils<
    typeof MembershipDataTableConfig.columns,
    typeof MembershipDataTableConfig.actions
  >;

  private readonly regularMember: MembershipFormDataType;
  private readonly studentMember: MembershipFormDataType;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.searchMemberField = {
      description: 'Filtering member name field from table',
      value: 'search-member-field',
    };
    this.exportToCsvButton = {
      description: 'Export to CSV button for member list',
      value: 'export-to-csv',
    };
    this.addMemberModalButton = { description: '', value: 'add-member-modal-btn' };

    this.memberTable = new TableUtils(page, MembershipDataTableConfig);

    this.regularMember = DataProvider.getApplyRegularMembershipFormData();
    this.studentMember = DataProvider.getApplyStudentMembershipFormData();
  }

  private async searchMember(text: string): Promise<void> {
    return await this.elements.fillElement(this.searchMemberField, text);
  }

  private async clickExportToCsvButton(): Promise<void> {
    return await this.elements.clickElement(this.exportToCsvButton);
  }

  public async clickAddMemberModalButton(): Promise<void> {
    return await this.elements.clickElement(this.addMemberModalButton);
  }

  private get uniqueKeys(): MembershipDataTableUniqueKeys {
    return {
      phone: this.regularMember.phone,
      email: this.regularMember.email,
    };
  }

  private get regularMemberData(): MembershipFormDataType {
    return DataProvider.getApplyRegularMembershipFormData();
  }

  public async clickViewMemberOfMember(): Promise<void> {
    return await this.memberTable.clickAction(this.uniqueKeys, 'viewMember');
  }

  public async clickEditMemberofMember(): Promise<void> {
    return await this.memberTable.clickAction(this.uniqueKeys, 'editMember');
  }

  public async clickViewMembershipOfMember(): Promise<void> {
    return await this.memberTable.clickAction(this.uniqueKeys, 'viewMembership');
  }

  public async clickAddMembershipOfMember(): Promise<void> {
    return await this.memberTable.clickAction(this.uniqueKeys, 'addMembership');
  }
  private async basicAssertMemberDataAvailable(regular: boolean, active: boolean): Promise<void> {
    const rowData = await this.memberTable.getRowData(this.uniqueKeys);
    const memberType = regular ? 'REGULAR' : 'STUDENT';
    const status = active ? 'ACTIVE' : 'INACTIVE';

    this.assertion.expectStringToEqual(rowData.name, this.regularMemberData.name);
    this.assertion.expectStringToEqual(rowData.email, this.regularMemberData.email);
    this.assertion.expectStringToEqual(rowData.phone, this.regularMemberData.phone);
    this.assertion.expectStringToEqual(rowData.type, memberType);
    this.assertion.expectStringToEqual(rowData.status, status);
  }
  public async assertIfRegularMemberDataAvailable(): Promise<void> {
    return await this.basicAssertMemberDataAvailable(true, true);
  }

  public async assertIfStudentMemberDataAvailable(): Promise<void> {
    return await this.basicAssertMemberDataAvailable(false, true);
  }
}
