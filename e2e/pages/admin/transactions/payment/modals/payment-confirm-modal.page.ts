import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import { PersonalDataType } from '#types/personal.type.js';
import DataProvider from '#utils/data.provider.utils.js';
import { APIRequestContext, Page } from '@playwright/test';

export default class ConfirmPaymentPage extends BasePage {
  private readonly modalTitle: LocatorType;
  private readonly customerName: LocatorType;
  private readonly customerPhone: LocatorType;
  private readonly totalPrice: LocatorType;

  private readonly cancelButton: LocatorType;
  private readonly yesButton: LocatorType;

  private readonly personalDetails: PersonalDataType;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);

    this.modalTitle = { value: 'confirm-payment-modal-title', description: '' };
    this.customerName = { value: 'confirm-customer-name', description: '' };
    this.customerPhone = { value: 'confirm-customer-phone', description: '' };
    this.totalPrice = { value: 'confirm-total-price', description: '' };
    this.cancelButton = { value: 'confirm-cancel-button', description: '' };
    this.yesButton = { value: 'confirm-yes-button', description: '' };

    this.personalDetails = DataProvider.getPersonalDetails();
  }

  public async clickYesButton(): Promise<void> {
    await this.assertion.elementVisible(this.modalTitle);

    await this.assertion.expectToHaveText(this.customerName, this.personalDetails.fullname);

    await this.assertion.expectToHaveText(this.customerPhone, this.personalDetails.phone);

    await this.elements.clickElement(this.yesButton);

    await this.assertion.elementNotVisible(this.modalTitle);
  }
}
