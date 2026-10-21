import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import { MembershipPackageDataType } from '#types/page/member.type.js';
import { PersonalDataType } from '#types/personal.type.js';
import DataProvider from '#utils/data.provider.utils.js';
import { APIRequestContext, Page } from '@playwright/test';

export default class BuyMemberPassModalPage extends BasePage {
  private readonly memberPassModalTitle: LocatorType;
  private readonly memberPersonalCategory: LocatorType;
  private readonly memberMembershipPackage: LocatorType;
  private readonly memberPrice: LocatorType;
  private readonly memberFullname: LocatorType;
  private readonly memberGender: LocatorType;
  private readonly memberDob: LocatorType;
  private readonly memberPhone: LocatorType;
  private readonly memberEmail: LocatorType;
  private readonly memberAddress: LocatorType;
  private readonly memberCreateButton: LocatorType;
  private readonly memberCancelButton: LocatorType;

  private readonly personalDetails: PersonalDataType;
  private readonly studentMembership: MembershipPackageDataType;
  private readonly regularMembership: MembershipPackageDataType;

  private readonly regular: boolean = true;
  private readonly student: boolean = false;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.memberPassModalTitle = { description: '', value: 'member-modal-title' };
    this.memberPersonalCategory = { description: '', value: 'member-personal-category' };
    this.memberMembershipPackage = { description: '', value: 'member-membership-package' };
    this.memberPrice = { description: '', value: 'member-price' };
    this.memberFullname = { description: '', value: 'member-fullname' };
    this.memberGender = { description: '', value: 'member-gender' };
    this.memberDob = { description: '', value: 'member-dob' };
    this.memberPhone = { description: '', value: 'member-phone' };
    this.memberEmail = { description: '', value: 'member-email' };
    this.memberAddress = { description: '', value: 'member-address' };
    this.memberCreateButton = { description: '', value: 'member-create' };
    this.memberCancelButton = { description: '', value: 'member-cancel' };

    this.personalDetails = DataProvider.getPersonalDetails();
    this.studentMembership = DataProvider.studentMemberMonthly();
    this.regularMembership = DataProvider.regularMemberMonthly();
  }

  private async selectPersonalCategory(category: string): Promise<void> {
    await this.elements.selectByValue(this.memberPersonalCategory, category);
    await this.assertion.expectToHaveValue(this.memberPersonalCategory, category);
  }

  private async selectMembershipPackage(packages: string): Promise<void> {
    // await this.elements.selectByLabel(this.memberMembershipPackage, packages);
    // await this.assertion.expectToHaveValue(this.memberMembershipPackage, packages);
    await this.elements.selectByCertainAttribute(
      this.memberMembershipPackage,
      'data-name',
      packages,
    );
  }

  private async getPrice(): Promise<string> {
    return await this.elements.getElementValue(this.memberPrice);
  }

  private async fillFullname(name: string): Promise<void> {
    await this.elements.fillElement(this.memberFullname, name);
    await this.assertion.expectToHaveValue(this.memberFullname, name);
  }

  private async selectGender(gender: string): Promise<void> {
    await this.elements.selectByValue(this.memberGender, gender);
    await this.assertion.expectToHaveValue(this.memberGender, gender);
  }

  private async fillDateOfBirth(dob: string): Promise<void> {
    await this.elements.fillElement(this.memberDob, dob);
    await this.assertion.expectToHaveValue(this.memberDob, dob);
  }

  private async fillPhoneNumber(phone: string): Promise<void> {
    await this.elements.fillElement(this.memberPhone, phone);
    await this.assertion.expectToHaveValue(this.memberPhone, phone);
  }

  private async fillEmailAddress(email: string): Promise<void> {
    await this.elements.fillElement(this.memberEmail, email);
    await this.assertion.expectToHaveValue(this.memberEmail, email);
  }

  private async fillStreetAddress(address: string): Promise<void> {
    await this.elements.fillElement(this.memberAddress, address);
    await this.assertion.expectToHaveValue(this.memberAddress, address);
  }

  private async createMembership(regular: boolean): Promise<void> {
    await this.selectPersonalCategory(
      regular ? this.regularMembership.personal_category : this.studentMembership.personal_category,
    );

    await this.selectMembershipPackage(
      regular
        ? this.regularMembership.membership_package
        : this.studentMembership.membership_package,
    );

    const price = await this.getPrice();
    this.assertion.expectStringToEqual(
      regular ? this.regularMembership.price : this.studentMembership.price,
      price,
    );

    await this.fillFullname(this.personalDetails.fullname);
    await this.selectGender(this.personalDetails.gender);
    await this.fillDateOfBirth(this.personalDetails.dob);
    await this.fillPhoneNumber(this.personalDetails.phone);
    await this.fillEmailAddress(this.personalDetails.email);
    await this.fillStreetAddress(this.personalDetails.address);

    await this.elements.clickElement(this.memberCreateButton);
    await this.assertion.elementNotVisible(this.memberPassModalTitle);
  }

  public async createRegularMembership(): Promise<void> {
    await this.createMembership(this.regular);
  }

  public async createStudentMembership(): Promise<void> {
    await this.createMembership(this.student);
  }
}
