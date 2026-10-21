import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import { APIRequestContext, Page } from '@playwright/test';

export default class RequestSubmittedPage extends BasePage {
  private readonly waitingForApproval: LocatorType;
  private readonly awaitingForPayment: LocatorType;
  private readonly sendWhatsAppLink: LocatorType;
  private readonly readyForPayment: LocatorType;
  private readonly onlinePaymentLink: LocatorType;
  private readonly offlineQRImage: LocatorType;
  private readonly offlineAccessToken: LocatorType;
  private readonly paymentReceived: LocatorType;
  private readonly viewQrAccessButton: LocatorType;
  private readonly rejectedRequest: LocatorType;
  private readonly paymentCompleted: LocatorType;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.waitingForApproval = {
      description: 'Waiting for approval message when student request purchasing',
      value: 'waiting-for-approval',
    };
    this.sendWhatsAppLink = {
      description:
        'A button to navigate to open whatsapp and create message to request approval for student membership / casual purchasing',
      value: 'send-whatsapp-link',
    };

    this.awaitingForPayment = {
      description: 'a message when regular member/casual need to proceed to offline payment',
      value: 'offline-awaiting-payment-span',
    };
    this.offlineQRImage = {
      description: 'QR Code that can be used of offline payment to admin',
      value: 'offline-qr-image',
    };
    this.offlineAccessToken = {
      description: 'An access token that could be provided to admin to proceed offline payment',
      value: 'offline-access-token',
    };

    this.readyForPayment = {
      description:
        'A message when online payment is enabled and the process could be done by clicking the button',
      value: 'online-for-payment-span',
    };
    this.onlinePaymentLink = {
      description: 'A button to proceed online payment if the feature is enabled',
      value: 'online-payment-link',
    };
    this.paymentReceived = {
      description: 'A message when payment is received',
      value: 'payment-received',
    };

    this.paymentCompleted = {
      description: 'A message when payment is completed',
      value: 'payment-completed-span',
    };
    this.viewQrAccessButton = {
      description: 'A button to get QR Code access to gym facility when payment is settled',
      value: 'view-access-qr-button',
    };

    this.rejectedRequest = {
      description: 'Message when a membership / casual request for student is rejected',
      value: 'rejected-request',
    };
  }

  public async checkStudentWaitingForApproval(): Promise<void> {
    await this.assertion.elementVisible(this.waitingForApproval);
    await this.assertion.elementVisible(this.sendWhatsAppLink);
  }

  public async checkOfflinePaymentReady(): Promise<void> {
    await this.assertion.elementVisible(this.awaitingForPayment);
    await this.assertion.elementVisible(this.offlineQRImage);
    await this.assertion.elementVisible(this.offlineAccessToken);
  }

  public async checkPaymentCompleted(): Promise<void> {
    return await this.assertion.elementVisible(this.paymentCompleted);
  }

  public async clickViewQrAccessButton(): Promise<void> {
    return await this.elements.clickElement(this.viewQrAccessButton);
  }
}
