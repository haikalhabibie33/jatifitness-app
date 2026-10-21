import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import DataProvider from '#utils/data.provider.utils.js';
import { APIRequestContext, Page } from '@playwright/test';

export default class ViewMemberModalPage extends BasePage {
  private readonly modalTitle: LocatorType;
  private readonly name: LocatorType;
  private readonly dob: LocatorType;
  private readonly gender: LocatorType;
  private readonly targetType: LocatorType;
  private readonly phone: LocatorType;
  private readonly email: LocatorType;
  private readonly address: LocatorType;

  private readonly closeModalBtn: LocatorType;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.modalTitle = { description: '', value: 'view-member-modal-title' };
    this.name = { description: '', value: 'view-member-name' };
    this.dob = { description: '', value: 'view-member-dob' };
    this.gender = { description: '', value: 'view-member-gender' };
    this.targetType = { description: '', value: 'view-member-target-type' };
    this.phone = { description: '', value: 'view-member-phone' };
    this.email = { description: '', value: 'view-member-email' };
    this.address = { description: '', value: 'view-member-address' };
    this.closeModalBtn = { description: '', value: 'view-member-close-btn' };
  }

  private async assertMemberDetails(regular: boolean): Promise<void> {
    await this.assertion.elementVisible(this.modalTitle);

    const data = DataProvider.getPersonalDetails();
    const assertedData = {
      ...data,
      target_type: regular ? 'REGULAR' : 'STUDENT',
    };
    await this.assertion.expectToHaveText(this.name, assertedData.fullname);
    await this.assertion.expectToHaveText(this.dob, assertedData.dob);
    await this.assertion.expectToHaveText(this.gender, assertedData.gender);
    await this.assertion.expectToHaveText(this.targetType, assertedData.target_type);
    await this.assertion.expectToHaveText(this.phone, assertedData.phone);
    await this.assertion.expectToHaveText(this.email, assertedData.email);
    await this.assertion.expectToHaveText(this.address, assertedData.address);
  }

  public async assertRegularMemberDetails(): Promise<void> {
    await this.assertMemberDetails(true);
  }

  public async assertStudentMemberDetails(): Promise<void> {
    await this.assertMemberDetails(false);
  }
}
