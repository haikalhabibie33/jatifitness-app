import { TableActions, TableColumns, TableConfig, RowCriteria } from '#types/table.config.type.js';
import { Page, Locator, expect } from '@playwright/test';

export default class TableUtils<
  TColumns extends TableColumns = TableColumns,
  TActions extends TableActions = TableActions,
> {
  private readonly page: Page;
  private readonly config: TableConfig<TColumns, TActions>;

  private readonly timeout: number = 5000;

  constructor(page: Page, config: TableConfig<TColumns, TActions>) {
    this.page = page;
    this.config = config;
  }

  private normalizeText(text: string | null | undefined): string {
    return (text ?? '').replace(/\s+/g, ' ').trim();
  }

  private get tableRoot(): Locator {
    return this.page.getByTestId(this.config.table);
  }

  private get rows(): Locator {
    return this.tableRoot.getByTestId(this.config.row);
  }

  private get emptyStateLocator(): Locator | null {
    return this.config.emptyState ? this.page.getByTestId(this.config.emptyState) : null;
  }

  private async waitUntilReady(timeout: number = this.timeout): Promise<void> {
    await expect
      .poll(
        async () => {
          if (this.emptyStateLocator) {
            const emptyVisible = await this.emptyStateLocator.isVisible().catch(() => false);
            if (emptyVisible) return 'empty';
          }

          const tableExists = await this.tableRoot.count().catch(() => 0);
          if (tableExists === 0) return 'pending';

          const rowCount = await this.rows.count().catch(() => 0);
          if (rowCount > 0) return 'rows';

          return 'pending';
        },
        { timeout },
      )
      .not.toBe('pending');
  }

  private async checkEmptyStateVisible(): Promise<boolean> {
    if (!this.emptyStateLocator) return false;
    return await this.emptyStateLocator.isVisible().catch(() => false);
  }

  public async isEmpty(): Promise<boolean> {
    await this.waitUntilReady();

    if (this.checkEmptyStateVisible()) {
      return true;
    }

    const rowCount = await this.rows.count().catch(() => 0);
    return rowCount === 0;
  }

  public async getRowCount(): Promise<number> {
    await this.waitUntilReady();

    if (await this.checkEmptyStateVisible()) return 0;
    return await this.rows.count();
  }

  public async readAll(): Promise<Array<Record<keyof TColumns & string, string>>> {
    if (await this.isEmpty()) return [];

    const count = await this.rows.count();
    const result: Array<Record<keyof TColumns & string, string>> = [];

    for (let i = 0; i < count; i++) {
      const row = this.rows.nth(i);
      const rowData = {} as Record<keyof TColumns & string, string>;

      for (const columnKey of Object.keys(this.config.columns) as Array<keyof TColumns & string>) {
        const testId = this.config.columns[columnKey];
        const value = await row.getByTestId(testId).textContent();
        rowData[columnKey] = this.normalizeText(value);
      }

      result.push(rowData);
    }

    return result;
  }

  public async findRow(criteria: RowCriteria<TColumns>): Promise<Locator> {
    await this.waitUntilReady();

    if (Object.keys(criteria).length === 0) {
      throw new Error('findRow criteria must not be empty');
    }

    if (await this.checkEmptyStateVisible()) {
      throw new Error('Table is empty.');
    }

    const count = await this.rows.count();

    for (let i = 0; i < count; i++) {
      const row = this.rows.nth(i);
      let matched = true;

      for (const key of Object.keys(criteria) as Array<keyof TColumns & string>) {
        const expected = criteria[key];
        const testId = this.config.columns[key];
        const actual = this.normalizeText(await row.getByTestId(testId).textContent());

        if (actual !== this.normalizeText(expected)) {
          matched = false;
          break;
        }
      }

      if (matched) return row;
    }

    throw new Error(`No row found for criteria: ${JSON.stringify(criteria)}`);
  }

  public async clickAction(
    criteria: RowCriteria<TColumns>,
    actionName: keyof TActions & string,
  ): Promise<void> {
    if (!this.config.actions) {
      throw new Error('No actions configured for this table.');
    }

    const actionTestId = this.config.actions[actionName];

    if (!actionTestId) {
      throw new Error(`Action "${actionName}" is not configured.`);
    }

    const row = await this.findRow(criteria);
    await row.getByTestId(actionTestId).click();
  }

  public async getCellValue(
    criteria: RowCriteria<TColumns>,
    columnName: keyof TColumns & string,
  ): Promise<string> {
    const row = await this.findRow(criteria);
    const testId = this.config.columns[columnName];
    const value = await row.getByTestId(testId).textContent();
    return this.normalizeText(value);
  }

  public async getRowData(
    criteria: RowCriteria<TColumns>,
  ): Promise<Record<keyof TColumns & string, string>> {
    const row = await this.findRow(criteria);
    const rowData = {} as Record<keyof TColumns & string, string>;

    for (const columnKey of Object.keys(this.config.columns) as Array<keyof TColumns & string>) {
      const testId = this.config.columns[columnKey];
      const value = await row.getByTestId(testId).textContent();
      rowData[columnKey] = this.normalizeText(value);
    }
    return rowData;
  }
}
