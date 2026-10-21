import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import { PersonalDataType } from '#types/personal.type.js';
import DataProvider from '#utils/data.provider.utils.js';
import { APIRequestContext, Page } from '@playwright/test';

export default class EditMemberModalPage extends BasePage {
  private readonly modaltitle: LocatorType;
  private readonly name: LocatorType;
  private readonly gender: LocatorType;
  private readonly dob: LocatorType;
  private readonly memberType: LocatorType;
  private readonly phone: LocatorType;
  private readonly email: LocatorType;
  private readonly address: LocatorType;

  private readonly status: LocatorType;

  // private readonly cancelBtn: LocatorType;
  private readonly submitBtn: LocatorType;

  private readonly editedPersonalDetails: PersonalDataType;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.modaltitle = { description: '', value: 'edit-member-modal-title' };
    this.name = { description: '', value: 'edit-member-name' };
    this.gender = { description: '', value: 'edit-member-gender' };
    this.dob = { description: '', value: 'edit-member-dob' };
    this.memberType = { description: '', value: 'edit-member-member-type' };
    this.phone = { description: '', value: 'edit-member-phone' };
    this.email = { description: '', value: 'edit-member-email' };
    this.address = { description: '', value: 'edit-member-address' };
    this.status = { description: '', value: 'edit-member-is-active' };
    // this.cancelBtn = {description:'',value:'edit-member-cancel-btn'};
    this.submitBtn = { description: '', value: 'edit-member-submit-btn' };

    this.editedPersonalDetails = DataProvider.getEditedPersonalDetails();
  }

  // private get dataInputModify() {
  //     return {
  //         name: 'Avada Cadabra',
  //         gender: 'FEMALE',
  //         dob: '1995-01-01',
  //         phone: '087629652415',
  //         email: 'test@test.com',
  //         address: 'Cocoyashi'
  //     }
  // }
  private async modifyName(name: string): Promise<void> {
    await this.elements.fillElement(this.name, name);
    await this.assertion.expectToHaveValue(this.name, name);
  }

  private async modifyGender(gender: string): Promise<void> {
    await this.elements.selectByValue(this.gender, gender);
    await this.assertion.expectToHaveValue(this.gender, gender);
  }

  private async modifyDob(dob: string): Promise<void> {
    await this.elements.fillElement(this.dob, dob);
    await this.assertion.expectToHaveValue(this.dob, dob);
  }

  private async getMemberType(): Promise<string> {
    return await this.elements.getElementValue(this.memberType);
  }

  private async modifyMemberType(memberType: string): Promise<void> {
    await this.elements.selectByValue(this.memberType, memberType);
    await this.assertion.expectToHaveValue(this.memberType, memberType);
  }

  private async modifyPhone(phone: string): Promise<void> {
    await this.elements.fillElement(this.phone, phone);
    await this.assertion.expectToHaveValue(this.phone, phone);
  }

  private async modifyEmail(email: string): Promise<void> {
    await this.elements.fillElement(this.email, email);
    await this.assertion.expectToHaveValue(this.email, email);
  }

  private async modifyAddress(address: string): Promise<void> {
    await this.elements.fillElement(this.address, address);
    await this.assertion.expectToHaveValue(this.address, address);
  }

  private async isStatusChecked(): Promise<boolean> {
    return await this.elements.isCheckboxChecked(this.status);
  }

  private async modifyStatus(active: boolean): Promise<void> {
    if (active) {
      await this.elements.checkInputCheckbox(this.status);
      await this.assertion.expectCheckboxChecked(this.status);
    } else {
      await this.elements.uncheckInputCheckbox(this.status);
      await this.assertion.expectCheckboxUnchecked(this.status);
    }
    // active ? await this.elements.checkInputCheckbox(this.status)
    // : await this.elements.uncheckInputCheckbox(this.status);

    // active ? await this.assertion.expectCheckboxChecked(this.status)
    // : await this.assertion.expectCheckboxUnchecked(this.status);
  }

  private async clickSaveButton(): Promise<void> {
    await this.elements.clickElement(this.submitBtn);
  }

  public async modifyAllFields(): Promise<void> {
    const memberType = await this.getMemberType();
    const inputMemberType = memberType === 'REGULAR' ? 'STUDENT' : 'REGULAR';

    const status = await this.isStatusChecked();
    const inputStatus = !status;

    await this.modifyName(this.editedPersonalDetails.fullname);
    await this.modifyGender(this.editedPersonalDetails.gender);
    await this.modifyPhone(this.editedPersonalDetails.phone);
    await this.modifyEmail(this.editedPersonalDetails.email);
    await this.modifyDob(this.editedPersonalDetails.dob);
    await this.modifyMemberType(inputMemberType);
    await this.modifyAddress(this.editedPersonalDetails.address);
    await this.modifyStatus(inputStatus);

    await this.clickSaveButton();
    await this.assertion.elementNotVisible(this.modaltitle);
  }

  public async modifySomeFields(): Promise<void> {
    await this.modifyName(this.editedPersonalDetails.fullname);
    await this.modifyGender(this.editedPersonalDetails.gender);
    await this.modifyDob(this.editedPersonalDetails.dob);
    await this.modifyAddress(this.editedPersonalDetails.address);

    await this.clickSaveButton();
    await this.assertion.elementNotVisible(this.modaltitle);
  }
}
