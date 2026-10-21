import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import { PersonalDataType } from '#types/personal.type.js';
import DataProvider from '#utils/data.provider.utils.js';
import { APIRequestContext, Page } from '@playwright/test';

export default class AddMemberModalPage extends BasePage {
  private readonly modalTitle: LocatorType;
  private readonly name: LocatorType;
  private readonly gender: LocatorType;
  private readonly dob: LocatorType;
  private readonly memberType: LocatorType;
  private readonly phone: LocatorType;
  private readonly email: LocatorType;
  private readonly address: LocatorType;
  private readonly status: LocatorType;

  private readonly cancelButton: LocatorType;
  private readonly saveButton: LocatorType;

  private readonly memberData: PersonalDataType;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.modalTitle = { description: '', value: 'add-member-modal-title' };
    this.name = { description: '', value: 'add-member-name' };
    this.gender = { description: '', value: 'add-member-gender' };
    this.dob = { description: '', value: 'add-member-dob' };
    this.memberType = { description: '', value: 'add-member-member-type' };
    this.phone = { description: '', value: 'add-member-phone' };
    this.email = { description: '', value: 'add-member-email' };
    this.address = { description: '', value: 'add-member-address' };
    this.status = { description: '', value: 'add-member-member-active-status' };
    this.cancelButton = { description: '', value: 'add-member-cancel-btn' };
    this.saveButton = { description: '', value: 'add-member-save-btn' };

    this.memberData = DataProvider.getPersonalDetails();
  }

  private async createMember(regular: boolean = true): Promise<void> {
    await this.assertion.elementVisible(this.modalTitle);

    await this.elements.fillElement(this.name, this.memberData.fullname);
    await this.assertion.expectToHaveValue(this.name, this.memberData.fullname);

    await this.elements.selectByValue(this.gender, this.memberData.gender);
    await this.assertion.expectToHaveValue(this.gender, this.memberData.gender);

    await this.elements.fillElement(this.dob, this.memberData.dob);
    await this.assertion.expectToHaveValue(this.dob, this.memberData.dob);

    const chooseMemberType = regular ? 'REGULAR' : 'STUDENT';
    await this.elements.selectByValue(this.memberType, chooseMemberType);
    await this.assertion.expectToHaveValue(this.memberType, chooseMemberType);

    await this.elements.fillElement(this.phone, this.memberData.phone);
    await this.assertion.expectToHaveValue(this.phone, this.memberData.phone);

    await this.elements.fillElement(this.email, this.memberData.email);
    await this.assertion.expectToHaveValue(this.email, this.memberData.email);

    await this.elements.fillElement(this.address, this.memberData.address);
    await this.assertion.expectToHaveValue(this.address, this.memberData.address);

    await this.elements.clickElement(this.saveButton);
  }

  public async createRegularMember(regular: boolean = true) {
    return await this.createMember(regular);
  }

  public async createStudentMember(regular: boolean = false) {
    return await this.createMember(regular);
  }
}
