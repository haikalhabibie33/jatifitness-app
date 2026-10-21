import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import { PersonalDataType } from '#types/personal.type.js';
import DataProvider from '#utils/data.provider.utils.js';
import {
  MEMBER_REGULAR_PACKAGE,
  MEMBER_REGULAR_PRICE,
  MEMBER_STUDENT_PACKAGE,
  MEMBER_STUDENT_PRICE,
  PERSONAL_CATEGORY,
} from '#utils/enum.utils.js';
import { APIRequestContext, Page } from '@playwright/test';

export default class AddMembershipModalPage extends BasePage {
  private readonly modalTitle: LocatorType;
  private readonly membershipPlan: LocatorType;
  private readonly name: LocatorType;
  private readonly targetType: LocatorType;
  private readonly cancelBtn: LocatorType;
  private readonly createBtn: LocatorType;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.modalTitle = { description: '', value: 'add-membership-modal-title' };
    this.membershipPlan = { description: '', value: 'add-membership-plan' };
    this.name = { description: '', value: 'add-membership-name' };
    this.targetType = { description: '', value: 'add-membership-target-type' };
    this.cancelBtn = { description: '', value: 'add-membership-cancel-btn' };
    this.createBtn = { description: '', value: 'add-membership-create-btn' };
  }

  private async clickCancelButton(): Promise<void> {
    return await this.elements.clickElement(this.cancelBtn);
  }

  private async clickCreateButton(): Promise<void> {
    return await this.elements.clickElement(this.createBtn);
  }

  private formattedPrice(price: string): string {
    const formattedPrice = Number(price).toLocaleString('id-ID', {
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    });

    return String(formattedPrice);
  }

  private get personalDetails(): PersonalDataType {
    return DataProvider.getPersonalDetails();
  }

  private async addMembership(regular: boolean): Promise<void> {
    await this.assertion.elementVisible(this.modalTitle);

    const memberType = regular ? PERSONAL_CATEGORY.REGULAR : PERSONAL_CATEGORY.STUDENT;

    const membership = regular ? MEMBER_REGULAR_PACKAGE.MONTHLY : MEMBER_STUDENT_PACKAGE.MONTHLY;

    const price = regular ? MEMBER_REGULAR_PRICE.MONTHLY : MEMBER_STUDENT_PRICE.MONTHLY;

    const membershipPlan = `${membership} (${this.formattedPrice(price)})`;
    await this.elements.selectByLabel(this.membershipPlan, membershipPlan);

    await this.assertion.expectToHaveValue(this.name, this.personalDetails.fullname);
    await this.assertion.expectToHaveValue(this.targetType, memberType);

    await this.clickCreateButton();
    await this.assertion.elementNotVisible(this.modalTitle);
  }

  public async addRegularMembership(): Promise<void> {
    return this.addMembership(true);
  }

  public async addStudentMembership(): Promise<void> {
    return this.addMembership(false);
  }
}
