import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import DataProvider from '#utils/data.provider.utils.js';
import { SUMMARY_MODAL_TITLE } from '#utils/enum.utils.js';
import { APIRequestContext, APIResponse, Page } from '@playwright/test';

export default class SummaryModalPage extends BasePage {
  private readonly title: LocatorType;
  private readonly category: LocatorType;
  private readonly packages: LocatorType;
  private readonly quantity: LocatorType;
  private readonly duration: LocatorType;

  private readonly unitPrice: LocatorType;
  private readonly totalPrice: LocatorType;

  private readonly name: LocatorType;
  private readonly email: LocatorType;
  private readonly phone: LocatorType;

  private readonly errorMessage: LocatorType;

  private readonly term: LocatorType;
  private readonly backEditButton: LocatorType;
  private readonly continuePayment: LocatorType;

  private readonly memberDataDetails = DataProvider.getPersonalDetails();
  private readonly targetTypeRegular: boolean = true;
  private readonly targetypeStudent: boolean = false;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.title = {
      description: 'Modal Title, should contain Casual or Member',
      value: 'modal-title',
    };
    this.category = { description: 'Personal Category', value: 'order-category' };
    this.packages = { description: 'Package Name choosen', value: 'order-package' };
    this.quantity = { description: 'Quantity of membership plan', value: 'order-quantity' };
    this.duration = { description: 'Total duration of membership plan', value: 'order-duration' };

    this.unitPrice = { description: 'Unit Price of membership plan', value: 'order-unit-price' };
    this.totalPrice = { description: 'Total Price of membership plan', value: 'order-total-price' };

    this.name = { description: 'Member name', value: 'order-name' };
    this.email = { description: 'member email', value: 'order-email' };
    this.phone = { description: 'Member phone', value: 'order-phone' };

    this.errorMessage = {
      description: 'Error Message: Please complete all required fields before continuing',
      value: 'error-message',
    };

    this.term = {
      description: 'By continuing, you agree that request cannot be edited after payment',
      value: 'order-term',
    };
    this.backEditButton = {
      description: 'Back to Edit button, dismiss the modal',
      value: 'back-edit-btn',
    };
    this.continuePayment = {
      description:
        'Continue to Payment button, proceed to get QR code for payment or waiting for approval if student',
      value: 'continue-payment-btn',
    };
  }

  private async assertMemberModalTitle(): Promise<void> {
    return await this.assertion.expectToHaveText(this.title, SUMMARY_MODAL_TITLE.MEMBER);
  }

  private async assertCasualModalTitle(): Promise<void> {
    return await this.assertion.expectToHaveText(this.title, SUMMARY_MODAL_TITLE.CASUAL);
  }

  private async getAndAssertPackageMemberDetails(regular: boolean = true): Promise<void> {
    const data = regular
      ? DataProvider.summaryMemberRegularPackageDetails()
      : DataProvider.summaryMemberStudentPackageDetails();

    await this.assertion.expectToHaveText(this.category, data.category);
    await this.assertion.expectToHaveText(this.packages, data.package);
    await this.assertion.expectToHaveText(this.quantity, data.quantity);
    await this.assertion.expectToHaveText(this.duration, data.totalDuration);
  }

  private async getAndAssertPackageCasualDetails(
    regular: boolean = true,
    quantity: number = 1,
  ): Promise<void> {
    const data = regular
      ? DataProvider.summaryCasualRegularPackageDetails(quantity)
      : DataProvider.summaryCasualStudentPackageDetails(quantity);

    await this.assertion.expectToHaveText(this.category, data.category);
    await this.assertion.expectToHaveText(this.packages, data.package);
    await this.assertion.expectToHaveText(this.quantity, data.quantity);
    await this.assertion.expectToHaveText(this.duration, data.totalDuration);
  }

  private async getAndAssertMemberPriceSummary(regular: boolean = true): Promise<void> {
    const data = regular
      ? DataProvider.summaryMemberRegularPriceSummary()
      : DataProvider.summaryMemberStudentPriceSummary();

    await this.assertion.expectToHaveText(this.unitPrice, data.unitPrice);
    await this.assertion.expectToHaveText(this.totalPrice, data.total);
  }

  private async getAndAssertCasualPriceSummary(
    regular: boolean = true,
    quantity: number = 1,
  ): Promise<void> {
    const data = regular
      ? DataProvider.summaryCasualRegularPriceSummary(quantity)
      : DataProvider.summaryCasualStudentPriceSummary(quantity);

    await this.assertion.expectToHaveText(this.unitPrice, data.unitPrice);
    await this.assertion.expectToHaveText(this.totalPrice, data.total);
  }

  private async getAndAssertApplicantInformation(): Promise<void> {
    const data = DataProvider.summaryApplicantInformation();

    await this.assertion.expectToHaveText(this.name, data.name);
    await this.assertion.expectToHaveText(this.email, data.email);
    await this.assertion.expectToHaveText(this.phone, data.phone);
  }
  public async assertAndProceedRegularMemberModal(): Promise<void> {
    await this.assertMemberModalTitle();

    await this.getAndAssertPackageMemberDetails(this.targetTypeRegular);
    await this.getAndAssertMemberPriceSummary(this.targetTypeRegular);
    await this.getAndAssertApplicantInformation();

    await this.elements.clickElement(this.continuePayment);
  }

  public async assertAndProceedStudentMemberModal(): Promise<void> {
    await this.assertMemberModalTitle();

    await this.getAndAssertPackageMemberDetails(this.targetypeStudent);
    await this.getAndAssertMemberPriceSummary(this.targetypeStudent);
    await this.getAndAssertApplicantInformation();

    await this.elements.clickElement(this.continuePayment);
  }

  public async assertAndProceedRegularCasualModal(quantity: number = 1): Promise<void> {
    await this.assertCasualModalTitle();

    await this.getAndAssertPackageCasualDetails(this.targetTypeRegular, quantity);
    await this.getAndAssertCasualPriceSummary(this.targetTypeRegular, quantity);
    await this.getAndAssertApplicantInformation();

    await this.elements.clickElement(this.continuePayment);
  }

  public async assertAndProceedStudentCasualModal(quantity: number = 1): Promise<void> {
    await this.assertCasualModalTitle();

    await this.getAndAssertPackageCasualDetails(this.targetypeStudent, quantity);
    await this.getAndAssertCasualPriceSummary(this.targetypeStudent, quantity);
    await this.getAndAssertApplicantInformation();

    await this.elements.clickElement(this.continuePayment);
  }

  public async confirmPaymentByAdmin(): Promise<void> {
    const request = await this.membershipRequestApiUtils.getMembershipRequestIdToken(
      this.memberDataDetails.phone,
      this.memberDataDetails.email,
    );
    await this.paymentApiUtils.paymentByAdminApi(Number(request.id), request.token);
  }

  public async approveStudentMembershipByAdmin(): Promise<APIResponse> {
    return await this.membershipRequestApiUtils.approveStudentByMembershipRequestId(
      this.memberDataDetails.phone,
      this.memberDataDetails.email,
    );
  }

  public async assertSummaryModalNotVisible(): Promise<void> {
    await this.assertion.elementNotVisible(this.title);
    await this.assertion.elementNotVisible(this.category);
    await this.assertion.elementNotVisible(this.continuePayment);
  }
}
