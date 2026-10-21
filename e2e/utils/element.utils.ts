import { expect, Locator, Page } from '@playwright/test';
import { LocatorType } from '#types/locator';

export default class Elements {
  private readonly page: Page;
  private readonly timeout: number = 3000;

  constructor(page: Page) {
    this.page = page;
  }

  private async waitElementTillVisible(locator: LocatorType) {
    await expect(this.page.getByTestId(locator.value)).toBeVisible({
      timeout: this.timeout,
    });
  }

  public async clickElement(locator: LocatorType, force: boolean = false): Promise<void> {
    await this.waitElementTillVisible(locator);
    return await this.page.getByTestId(locator.value).click({ force: force });
  }

  public async fillElement(locator: LocatorType, input: string): Promise<void> {
    await this.waitElementTillVisible(locator);
    return await this.page
      .getByTestId(locator.value)
      .fill(input, { force: true, timeout: this.timeout });
  }

  public async fillElementWithClear(
    locator: LocatorType,
    input: string,
    force: boolean = false,
  ): Promise<void> {
    await this.waitElementTillVisible(locator);
    await this.page.getByTestId(locator.value).clear({ force: force });
    return await this.page
      .getByTestId(locator.value)
      .fill(input, { force: true, timeout: this.timeout });
  }

  public async selectByLabel(locator: LocatorType, label: string): Promise<string[]> {
    await this.waitElementTillVisible(locator);
    await this.page.getByTestId(locator.value).click({ force: true, timeout: this.timeout });
    return await this.page
      .getByTestId(locator.value)
      .selectOption({ label: label }, { timeout: this.timeout });
  }

  public async selectByValue(locator: LocatorType, value: string): Promise<string[]> {
    await this.waitElementTillVisible(locator);
    await this.page.getByTestId(locator.value).click({ force: true, timeout: this.timeout });
    return await this.page
      .getByTestId(locator.value)
      .selectOption({ value: value }, { timeout: this.timeout });
  }

  public async selectByCertainAttribute(
    locator: LocatorType,
    attributeName: string,
    attributeValue: string,
  ) {
    await this.waitElementTillVisible(locator);

    const select = this.page.getByTestId(locator.value);
    const option = select.locator(`option[${attributeName} = "${attributeValue}"]`);
    const optionValue = await option.getAttribute('value');

    return await select.selectOption({ value: optionValue });
  }

  public async getValueFromInput(locator: LocatorType): Promise<string> {
    await this.waitElementTillVisible(locator);
    return await this.page.getByTestId(locator.value).inputValue({ timeout: this.timeout });
  }

  public async getTextContent(locator: LocatorType): Promise<string> {
    await this.waitElementTillVisible(locator);
    return await this.page.getByTestId(locator.value).textContent({ timeout: this.timeout });
  }

  public async getInnerText(locator: LocatorType): Promise<string> {
    await this.waitElementTillVisible(locator);
    return await this.page.getByTestId(locator.value).innerText({ timeout: this.timeout });
  }

  public async selectFromNiceSelectByDataValue(index: number = 0, option: string): Promise<void> {
    const dropdown: Locator = this.page.locator('.nice-select').nth(index);
    await dropdown.click();
    await dropdown.locator(`.option[data-value="${option}"]`).click();
  }

  public async selectFromNiceSelectByText(index: number = 0, option: string): Promise<void> {
    const dropdown: Locator = this.page.locator('.nice-select').nth(index);
    await dropdown.click({ force: true, timeout: this.timeout });
    await dropdown.getByText(option, { exact: true }).click();
  }

  public async isCheckboxChecked(locator: LocatorType): Promise<boolean> {
    return await this.page.getByTestId(locator.value).isChecked();
  }

  public async checkInputCheckbox(locator: LocatorType): Promise<void> {
    await this.page.getByTestId(locator.value).check();
  }

  public async uncheckInputCheckbox(locator: LocatorType): Promise<void> {
    await this.page.getByTestId(locator.value).uncheck();
  }

  public async getElementValue(locator: LocatorType): Promise<string> {
    return await this.page.getByTestId(locator.value).inputValue();
  }

  public async getElementText(locator: LocatorType): Promise<string> {
    return await this.page.getByTestId(locator.value).textContent();
  }
}
