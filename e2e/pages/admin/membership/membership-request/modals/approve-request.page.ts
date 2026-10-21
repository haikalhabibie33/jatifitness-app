import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import { APIRequestContext, Page } from '@playwright/test';

export default class ApproveMembershipRequestPage extends BasePage {
  private readonly modalTitle: LocatorType;

  private readonly requestId: LocatorType;
  private readonly name: LocatorType;
  private readonly requestType: LocatorType;
  private readonly adminNotes: LocatorType;

  private readonly cancelButton: LocatorType;
  private readonly approveButton: LocatorType;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.modalTitle = { description: '', value: 'approve-modal-title' };

    this.requestId = { description: '', value: 'approve-request-id' };
    this.name = { description: '', value: 'approve-customer-name' };
    this.requestType = { description: '', value: 'approve-request-type' };
    this.adminNotes = { description: '', value: 'approve-admin-note' };

    this.cancelButton = { description: '', value: 'approve-cancel-btn' };
    this.approveButton = { description: '', value: 'approve-approve-btn' };
  }

  private async getRequestIdFromModal(): Promise<string> {
    return await this.elements.getElementText(this.requestId);
  }

  private async getCustomerName(): Promise<string> {
    return await this.elements.getElementText(this.name);
  }

  private async getRequestType(): Promise<string> {
    return await this.elements.getElementText(this.requestType);
  }

  private async fillAdminNotes(text: string): Promise<void> {
    await this.elements.fillElement(this.adminNotes, text);
    await this.assertion.expectToHaveValue(this.adminNotes, text);
  }

  private async clickCancelButton(): Promise<void> {
    await this.elements.clickElement(this.cancelButton);
  }

  public async clickApproveButton(): Promise<void> {
    await this.elements.clickElement(this.approveButton);
  }
}
