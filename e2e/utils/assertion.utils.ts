import { Page, expect } from '@playwright/test';
import { LocatorType } from '../types/locator';

export default class Assertions {
  private readonly page: Page;
  private readonly timeout: number = 10000;

  constructor(page: Page) {
    this.page = page;
  }

  public async elementVisible(locator: LocatorType): Promise<void> {
    await expect
      .poll(
        async () => {
          return await this.page.getByTestId(locator.value).isVisible();
        },
        {
          timeout: this.timeout,
        },
      )
      .toBe(true);
  }

  public async elementNotVisible(locator: LocatorType): Promise<void> {
    await expect
      .poll(
        async () => {
          return await this.page.getByTestId(locator.value).isVisible();
        },
        {
          timeout: this.timeout,
        },
      )
      .toBe(false);
  }

  public async expectInputValueEqual(locator: LocatorType, expectedValue: string): Promise<void> {
    await expect
      .poll(
        async () => {
          return await this.page.getByTestId(locator.value).inputValue();
        },
        {
          timeout: this.timeout,
        },
      )
      .toEqual(expectedValue.trim());
  }

  public async expectToHaveValue(locator: LocatorType, expectedValue: string): Promise<void> {
    await expect
      .poll(
        async () => {
          return await this.page.getByTestId(locator.value).inputValue();
        },
        {
          timeout: this.timeout,
        },
      )
      .toBe(expectedValue.trim());
  }

  public async expectToHaveText(locator: LocatorType, expectedValue: string): Promise<void> {
    await expect
      .poll(
        async () => {
          return await this.page.getByTestId(locator.value).innerText();
        },
        {
          timeout: this.timeout,
        },
      )
      .toBe(expectedValue.trim());
  }

  public expectStringToEqual(expected: string, actual: string): void {
    const normalizeText = (text: string) => {
      text
        .replace(/\u00A0/g, ' ')
        .replace(/\s+/g, ' ')
        .trim();
    };
    return expect(normalizeText(expected)).toEqual(normalizeText(actual));
  }

  public expectInputTobeUndefined(actual: any): void {
    console.log('Actual Value', actual);
    return expect(actual).toBeUndefined();
  }

  public async expectCheckboxChecked(locator: LocatorType): Promise<void> {
    await expect(this.page.getByTestId(locator.value)).toBeChecked();
  }

  public async expectCheckboxUnchecked(locator: LocatorType): Promise<void> {
    await expect(this.page.getByTestId(locator.value)).not.toBeChecked();
  }
}
