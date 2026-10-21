import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import TableUtils from '#utils/table.utils.js';
import { APIRequestContext, Page } from '@playwright/test';
import {
  ViewMembershipDataTableColumns,
  ViewMembershipTableConfig,
} from './view-membership-details.table';

export default class ViewMembershipModalPage extends BasePage {
  private readonly modalTitle: LocatorType;
  private readonly cancelBtn: LocatorType;

  private readonly viewMembershipTable: TableUtils<
    typeof ViewMembershipTableConfig.columns,
    typeof ViewMembershipTableConfig.actions
  >;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.modalTitle = { description: '', value: 'view-membership-modal-title' };
    this.cancelBtn = { description: '', value: 'view-membership-cancel-btn' };

    this.viewMembershipTable = new TableUtils(page, ViewMembershipTableConfig);
  }

  private async getRowDataByIndex(
    index: number = 1,
  ): Promise<Record<keyof ViewMembershipDataTableColumns, string>> {
    const number = String(index);
    return await this.viewMembershipTable.getRowData({ number });
  }

  public async assertRowDataMonthly(index: number = 1): Promise<void> {
    const rowData = await this.getRowDataByIndex(index);

    this.assertion.expectStringToEqual(rowData.number, String(index));
    this.assertion.expectStringToEqual(rowData.planName, 'Regular Per 1 Month');

    this.dateUtils.assertDurationMonthly(rowData.startDate, rowData.endDate);
  }
}
