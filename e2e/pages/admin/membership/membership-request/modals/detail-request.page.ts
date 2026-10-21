import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import { APIRequestContext, Page } from '@playwright/test';

export default class DetailMembershipRequestPage extends BasePage {
  private readonly modalTitle: LocatorType;

  private readonly customerName: LocatorType;
  private readonly gender: LocatorType;
  private readonly dob: LocatorType;
  private readonly phone: LocatorType;
  private readonly email: LocatorType;
  private readonly requestType: LocatorType;
  private readonly memberType: LocatorType;

  private readonly membershipPlan: LocatorType;
  private readonly duration: LocatorType;
  private readonly quantity: LocatorType;
  private readonly totalPrice: LocatorType;
  private readonly paymentMethod: LocatorType;
  private readonly paymentStatus: LocatorType;

  private readonly closeButton: LocatorType;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.modalTitle = { description: '', value: 'detail-modal-title' };

    this.customerName = { description: '', value: 'detail-customer-name' };
    this.gender = { description: '', value: 'detail-gender' };
    this.dob = { description: '', value: 'detail-dob' };
    this.phone = { description: '', value: 'detail-phone' };
    this.email = { description: '', value: 'detail-email' };
    this.requestType = { description: 'CASUAL or MEMBER', value: 'detail-request-type' };
    this.memberType = { description: 'REGULAR or STUDENT', value: 'detail-member-type' };

    this.membershipPlan = { description: '', value: 'detail-membership-plan' };
    this.duration = { description: '', value: 'detail-duration' };
    this.quantity = { description: '', value: 'detail-quantity' };
    this.totalPrice = { description: '', value: 'detail-total-price' };
    this.paymentMethod = { description: 'QRIS, CASH, etc', value: 'detail-payment-method' };
    this.paymentStatus = { description: '', value: 'detail-payment-status' };

    this.closeButton = { description: '', value: 'detail-close-button' };
  }

  private async getCustomerName(): Promise<string> {
    return await this.elements.getElementText(this.customerName);
  }

  private async getGender(): Promise<string> {
    return await this.elements.getElementText(this.gender);
  }

  private async getDateOfBirth(): Promise<string> {
    return await this.elements.getElementText(this.dob);
  }

  private async getPhoneNumber(): Promise<string> {
    return await this.elements.getElementText(this.phone);
  }

  private async getEmail(): Promise<string> {
    return await this.elements.getElementText(this.email);
  }

  private async getRequestType(): Promise<string> {
    return await this.elements.getElementText(this.requestType);
  }

  private async getMemberType(): Promise<string> {
    return await this.elements.getElementText(this.memberType);
  }

  private async getMembershipPlan(): Promise<string> {
    return await this.elements.getElementText(this.membershipPlan);
  }

  private async getDuration(): Promise<string> {
    return await this.elements.getElementText(this.duration);
  }

  private async getQuantity(): Promise<string> {
    return await this.elements.getElementText(this.quantity);
  }

  private async getTotalPrice(): Promise<string> {
    return await this.elements.getElementText(this.totalPrice);
  }

  private async getPaymentMethod(): Promise<string> {
    return await this.elements.getElementText(this.paymentMethod);
  }

  private async getPaymentStatus(): Promise<string> {
    return await this.elements.getElementText(this.paymentStatus);
  }
}
