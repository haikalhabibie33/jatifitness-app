import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import { APIRequestContext, Page } from '@playwright/test';

export default class RejectMembershipRequestPage extends BasePage {
  private readonly modalTitle: LocatorType;
  private readonly requestId: LocatorType;
  private readonly customerName: LocatorType;
  private readonly requestType: LocatorType;
  private readonly reasonInput: LocatorType;

  private readonly cancelButton: LocatorType;
  private readonly submitButton: LocatorType;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.modalTitle = { description: '', value: 'reject-modal-title' };
    this.requestId = { description: '', value: 'reject-request-id' };
    this.customerName = { description: '', value: 'reject-customer-name' };
    this.requestType = { description: '', value: 'reject-request-type' };
    this.reasonInput = { description: '', value: 'reject-reason-note' };

    this.cancelButton = { description: '', value: 'reject-cancel-btn' };
    this.submitButton = { description: '', value: 'reject-submit-btn' };
  }

  private async getRequestId(): Promise<string> {
    return await this.elements.getElementText(this.requestId);
  }

  private async getCustomerName(): Promise<string> {
    return await this.elements.getElementText(this.customerName);
  }

  private async getRequestType(): Promise<string> {
    return await this.elements.getElementText(this.requestType);
  }

  private async fillReasonNote(text: string): Promise<void> {
    await this.elements.fillElement(this.reasonInput, text);
    await this.assertion.expectToHaveValue(this.reasonInput, text);
  }

  private async clickCancelButton(): Promise<void> {
    await this.elements.clickElement(this.cancelButton);
  }

  private async clickRejectButton(): Promise<void> {
    await this.elements.clickElement(this.submitButton);
  }
}
