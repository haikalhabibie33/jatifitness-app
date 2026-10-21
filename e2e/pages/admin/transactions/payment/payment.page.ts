import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import { PersonalDataType } from '#types/personal.type.js';
import DataProvider from '#utils/data.provider.utils.js';
import TableUtils from '#utils/table.utils.js';
import { APIRequestContext, Page } from '@playwright/test';
import { PaymentProcessingTableConfig } from './payment.table';

export default class PaymentPage extends BasePage {
  private readonly scanQRButton: LocatorType;
  private readonly stopScanQRButton: LocatorType;
  private readonly buyCasualPassesButton: LocatorType;
  private readonly buyMemberPassesButton: LocatorType;
  private readonly inputTokenOrScanQR: LocatorType;
  private readonly loadTokenOrScanQRButton: LocatorType;
  private readonly inputPhoneNumber: LocatorType;
  private readonly searchPhoneNumberButton: LocatorType;

  private readonly personalDetails: PersonalDataType;

  private readonly paymentTable: TableUtils<
    typeof PaymentProcessingTableConfig.columns,
    typeof PaymentProcessingTableConfig.actions
  >;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.scanQRButton = {
      description: 'Scan QR button to trigger QR scanning functionality',
      value: 'scan-qr-btn',
    };
    this.stopScanQRButton = {
      description: 'Stop Scan QR button to stop QR scanning functionality',
      value: 'stop-qr-btn',
    };
    this.buyCasualPassesButton = {
      description: 'Buy casual passes button, to trigger Buy Casual Passes Modal',
      value: 'buy-casual-passes',
    };
    this.buyMemberPassesButton = {
      description: 'Buy member passes button, to trigger Buy Member passes Modal',
      value: 'buy-member-passes',
    };
    this.inputTokenOrScanQR = {
      description: 'Paste token or scan QR field',
      value: 'input-token-qr',
    };
    this.loadTokenOrScanQRButton = { description: 'Load token button', value: 'load-token-qr-btn' };
    this.inputPhoneNumber = { description: 'Phone number input', value: 'phone-number-input' };
    this.searchPhoneNumberButton = {
      description: 'Search phone number button',
      value: 'load-phone-number-btn',
    };

    this.personalDetails = DataProvider.getPersonalDetails();
    this.paymentTable = new TableUtils(page, PaymentProcessingTableConfig);
  }

  private async clickScanQrButton(): Promise<void> {
    return await this.elements.clickElement(this.scanQRButton);
  }

  private async clickStopScanQRButton(): Promise<void> {
    return await this.elements.clickElement(this.stopScanQRButton);
  }

  public async clickBuyCasualPassesButton(): Promise<void> {
    await this.elements.clickElement(this.buyCasualPassesButton);
  }

  public async clickBuyMemberPassesButton(): Promise<void> {
    return await this.elements.clickElement(this.buyMemberPassesButton);
  }

  private async fillTokenOrScanQrInput(input: string): Promise<void> {
    return await this.elements.fillElement(this.inputTokenOrScanQR, input);
  }

  private async clickLoadTokenOrScanQrButton(): Promise<void> {
    return await this.elements.clickElement(this.loadTokenOrScanQRButton);
  }

  private async fillInputPhoneNumber(input: string): Promise<void> {
    return await this.elements.fillElement(this.inputPhoneNumber, input);
  }

  private async clickSearchPhoneNumberButton(): Promise<void> {
    return await this.elements.clickElement(this.searchPhoneNumberButton);
  }

  private async getAccessToken(): Promise<string> {
    const request = await this.membershipRequestApiUtils.getMembershipRequestIdToken(
      this.personalDetails.phone,
      this.personalDetails.email,
    );
    return request.token;
  }

  public async confirmPayment(): Promise<void> {
    const access_token = await this.getAccessToken();
    await this.paymentTable.clickAction({ access_token }, 'confirm');
  }

  public async inputPhoneAndConfirm(): Promise<void> {
    await this.fillInputPhoneNumber(this.personalDetails.phone);
    await this.clickSearchPhoneNumberButton();
    await this.confirmPayment();
  }
}
