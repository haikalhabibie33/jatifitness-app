import { BasePage } from '#pages/base.page';
import { LocatorType } from '#types/locator.js';
import { APIRequestContext, APIResponse, Page } from '@playwright/test';
import { WEB_PAGE } from '#utils/enum.utils';
import DataProvider from '#utils/data.provider.utils.js';
import { CasualPackageDataType, MembershipPackageDataType } from '#types/page/member.type.js';
import { Response } from '@playwright/test';
import { PersonalDataType } from '#types/personal.type.js';
import { MembershipFormDataType } from '#types/api-form/apply.membership.type.js';

export default class MemberPage extends BasePage {
  private readonly memberTab: LocatorType;
  private readonly casualTab: LocatorType;

  // private readonly memberPersonalCategory: LocatorType;
  // private readonly membershipPackage: LocatorType;
  private readonly memberDurationDays: LocatorType;
  private readonly memberPriceIDR: LocatorType;
  private readonly memberFullName: LocatorType;
  private readonly memberGender: LocatorType;
  private readonly memberDateOfBirth: LocatorType;
  private readonly memberPhoneNumber: LocatorType;
  private readonly memberEmailAddress: LocatorType;
  private readonly memberStreetAddress: LocatorType;
  private readonly applyMembership: LocatorType;
  private readonly errorApplyMemberMessage: LocatorType;

  // private readonly casualPersonalCategory: LocatorType;
  // private readonly casualPackage: LocatorType;
  private readonly casualQuantity: LocatorType;
  private readonly casualDurationDays: LocatorType;
  private readonly casualTotalPrice: LocatorType;
  private readonly casualFullname: LocatorType;
  private readonly casualPhoneNumber: LocatorType;
  private readonly casualEmailAddress: LocatorType;
  private readonly applyCasualAccess: LocatorType;

  private readonly personalData: PersonalDataType;
  private readonly duplicatePhoneAndEmailMemberPersonalData: PersonalDataType;
  private readonly duplicatePhoneMemberPersonalData: PersonalDataType;
  private readonly duplicateEmailMemberPersonalData: PersonalDataType;

  private readonly targetTypeStudent: boolean = false;
  private readonly targetTypeRegular: boolean = true;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.memberTab = { description: 'Member tab, to apply membership', value: 'form-member-tab' };
    this.casualTab = { description: 'Casual tab, to buy casual pass', value: 'form-casual-tab' };

    this.memberDurationDays = {
      description:
        'A readonly input field after selecting personal category and membership package',
      value: 'duration-input',
    };
    this.memberPriceIDR = {
      description:
        'A readonly input field after selecting personal category and membership package',
      value: 'price-input',
    };
    this.memberFullName = { description: 'Fullname input field', value: 'fullname-input' };
    this.memberGender = {
      description: 'Gender dropdown to select Male or Female',
      value: 'select-gender',
    };
    this.memberDateOfBirth = {
      description: 'Date of Birth input, a datepicker',
      value: 'dob-input',
    };
    this.memberPhoneNumber = { description: 'Phone Number input field', value: 'phone-input' };
    this.memberEmailAddress = { description: 'Email Address input field', value: 'email-input' };
    this.memberStreetAddress = { description: 'Street address input field', value: 'street-input' };
    this.applyMembership = {
      description: 'Apply Membership button to trigger summary modal, not submitting form',
      value: 'apply-button',
    };
    this.errorApplyMemberMessage = {
      description: 'Error message when there is duplicate email or phone with database',
      value: 'member-error-message',
    };

    this.casualQuantity = {
      description: 'Quantity input field, to increase days',
      value: 'casual-quantity-input',
    };
    this.casualDurationDays = {
      description: 'A readonly input to shows duration (days) after changing quantity input',
      value: 'casual-duration-input',
    };
    this.casualTotalPrice = {
      description: 'A readonly input to shows total price (IDR) after changing quantity input',
      value: 'casual-price-input',
    };
    this.casualFullname = { description: 'Fullname input field', value: 'casual-fullname-input' };
    this.casualPhoneNumber = {
      description: 'Phone number input field',
      value: 'casual-phone-input',
    };
    this.casualEmailAddress = {
      description: 'Email Address input field',
      value: 'casual-email-input',
    };
    this.applyCasualAccess = {
      description: 'Apply Casual Access button to trigger summary modal, not submitting form',
      value: 'apply-casual-button',
    };

    this.personalData = DataProvider.getPersonalDetails();
    this.duplicatePhoneAndEmailMemberPersonalData = DataProvider.getDuplicatePhoneAndEmailData();
    this.duplicatePhoneMemberPersonalData = DataProvider.getDuplicatePhoneOnlyData();
    this.duplicateEmailMemberPersonalData = DataProvider.getDuplicateEmailOnlyData();
  }

  public async gotoMemberPage(): Promise<Response | null> {
    return await this.page.goto(WEB_PAGE.MEMBER);
  }

  private async clickMemberTab(): Promise<void> {
    return await this.elements.clickElement(this.memberTab);
  }

  private async clickCasualTab(): Promise<void> {
    return await this.elements.clickElement(this.casualTab);
  }

  private async clickApplyMembership(): Promise<void> {
    return await this.elements.clickElement(this.applyMembership);
  }

  private async clickApplyCasualAccess(): Promise<void> {
    return await this.elements.clickElement(this.applyCasualAccess);
  }

  private async chooseMembershipPackage(regular: boolean = true): Promise<void> {
    const data: MembershipPackageDataType = regular
      ? DataProvider.regularMemberMonthly()
      : DataProvider.studentMemberMonthly();

    await this.elements.selectFromNiceSelectByDataValue(0, data.personal_category);
    await this.elements.selectFromNiceSelectByText(1, data.membership_package);

    await this.assertion.expectToHaveValue(this.memberDurationDays, data.durations);
    await this.assertion.expectToHaveValue(this.memberPriceIDR, data.price);
  }

  private async chooseCasualPackage(regular: boolean = true, quantity: number): Promise<void> {
    const data: CasualPackageDataType = regular
      ? DataProvider.regularCasualPackage()
      : DataProvider.studentCasualPackage();

    const totalPrice = quantity * Number(data.price);

    await this.elements.selectFromNiceSelectByDataValue(3, data.personal_category);
    await this.elements.selectFromNiceSelectByText(4, data.casual_package);
    await this.elements.fillElement(this.casualQuantity, String(quantity));

    await this.assertion.expectToHaveValue(this.casualQuantity, String(quantity));
    await this.assertion.expectToHaveValue(this.casualDurationDays, String(quantity));
    await this.assertion.expectToHaveValue(this.casualTotalPrice, String(totalPrice));
  }

  private async membershipPersonalDetails(data: PersonalDataType): Promise<void> {
    await this.elements.fillElement(this.memberFullName, data.fullname);
    await this.assertion.expectToHaveValue(this.memberFullName, data.fullname);

    await this.elements.selectFromNiceSelectByDataValue(2, data.gender);
    await this.assertion.expectToHaveValue(this.memberGender, data.gender);

    await this.elements.fillElement(this.memberDateOfBirth, data.dob);
    await this.assertion.expectToHaveValue(this.memberDateOfBirth, data.dob);

    await this.elements.fillElement(this.memberPhoneNumber, data.phone);
    await this.assertion.expectToHaveValue(this.memberPhoneNumber, data.phone);

    await this.elements.fillElement(this.memberEmailAddress, data.email);
    await this.assertion.expectToHaveValue(this.memberEmailAddress, data.email);

    await this.elements.fillElement(this.memberStreetAddress, data.address);
    await this.assertion.expectToHaveValue(this.memberStreetAddress, data.address);
  }

  private async casualPersonalDetails(
    data: PersonalDataType,
    phone: boolean = true,
    email: boolean = true,
  ): Promise<void> {
    await this.elements.fillElement(this.casualFullname, data.fullname);

    // phone && await this.elements.fillElement(this.casualPhoneNumber,data.phone);
    // phone && await this.assertion.expectToHaveValue(this.casualPhoneNumber,data.phone);
    if (phone) {
      await this.elements.fillElement(this.casualPhoneNumber, data.phone);
      await this.assertion.expectToHaveValue(this.casualPhoneNumber, data.phone);
    }

    if (email) {
      await this.elements.fillElement(this.casualEmailAddress, data.email);
      await this.assertion.expectToHaveValue(this.casualEmailAddress, data.email);
    }

    // email && await this.elements.fillElement(this.casualEmailAddress,data.email);
    // email && await this.assertion.expectToHaveValue(this.casualEmailAddress,data.email);
  }

  private async applyForRegularMembership(
    data: PersonalDataType,
    regular: boolean = true,
  ): Promise<void> {
    await this.clickMemberTab();
    await this.chooseMembershipPackage(regular);
    await this.membershipPersonalDetails(data);
    await this.clickApplyMembership();
  }

  private async applyForStudentMembership(
    data: PersonalDataType,
    regular: boolean = false,
  ): Promise<void> {
    await this.clickMemberTab();
    await this.chooseMembershipPackage(regular);
    await this.membershipPersonalDetails(data);
    await this.clickApplyMembership();
  }

  private async applyForMembershipWithConditions(
    data: PersonalDataType,
    regular: boolean = true,
    phone: boolean = true,
    email: boolean = true,
  ): Promise<void> {
    await this.clickMemberTab();
    await this.chooseMembershipPackage(regular);

    if (phone) {
      await this.elements.fillElement(this.memberPhoneNumber, data.phone);
      await this.assertion.expectToHaveValue(this.memberPhoneNumber, data.phone);
    }

    // phone && await this.elements.fillElement(this.memberPhoneNumber, data.phone);
    // phone && await this.assertion.expectToHaveValue(this.memberPhoneNumber, data.phone);

    if (email) {
      await this.elements.fillElement(this.memberEmailAddress, data.email);
      await this.assertion.expectToHaveValue(this.memberEmailAddress, data.email);
    }

    // email && await this.elements.fillElement(this.memberEmailAddress,data.email);
    // email && await this.assertion.expectToHaveValue(this.memberEmailAddress,data.email);

    await this.elements.fillElement(this.memberFullName, data.fullname);
    await this.assertion.expectToHaveValue(this.memberFullName, data.fullname);

    await this.elements.selectFromNiceSelectByDataValue(2, data.gender);
    await this.assertion.expectToHaveValue(this.memberGender, data.gender);

    await this.elements.fillElement(this.memberStreetAddress, data.address);
    await this.assertion.expectToHaveValue(this.memberStreetAddress, data.address);

    await this.clickApplyMembership();
  }

  /*
    // Regular membership tests
    */

  public async applyForRegularMember(): Promise<void> {
    await this.applyForRegularMembership(this.personalData);
  }

  public async applyForDuplicatePhoneAndEmailRegularMember(): Promise<void> {
    await this.applyForRegularMembership(this.duplicatePhoneAndEmailMemberPersonalData);
    await this.assertion.elementVisible(this.errorApplyMemberMessage);
  }

  public async applyForDuplicatePhoneOnlyRegularMember(): Promise<void> {
    await this.applyForRegularMembership(this.duplicatePhoneMemberPersonalData);
    await this.assertion.elementVisible(this.errorApplyMemberMessage);
  }

  public async applyForDuplicateEmailOnlyRegularMember(): Promise<void> {
    await this.applyForRegularMembership(this.duplicateEmailMemberPersonalData);
    await this.assertion.elementVisible(this.errorApplyMemberMessage);
  }

  public async applyRegularMembershipWithoutPhoneEmail(): Promise<void> {
    return await this.applyForMembershipWithConditions(this.personalData, true, false, false);
  }

  public async applyRegularMembershipWithoutPhoneOnly(): Promise<void> {
    return await this.applyForMembershipWithConditions(this.personalData, true, false, true);
  }

  public async applyRegularMembershipWithoutEmailOnly(): Promise<void> {
    return await this.applyForMembershipWithConditions(this.personalData, true, true, false);
  }

  /* 
    // Student membership tests
    */
  public async applyForStudentMember(): Promise<void> {
    await this.applyForStudentMembership(this.personalData, false);
  }

  public async applyForDuplicatePhoneAndEmailStudentMember(): Promise<void> {
    await this.applyForStudentMembership(this.duplicatePhoneAndEmailMemberPersonalData);
    await this.assertion.elementVisible(this.errorApplyMemberMessage);
  }

  public async applyForDuplicatePhoneOnlyStudentMember(): Promise<void> {
    await this.applyForStudentMembership(this.duplicatePhoneMemberPersonalData);
    await this.assertion.elementVisible(this.errorApplyMemberMessage);
  }

  public async applyForDuplicateEmailOnlyStudentMember(): Promise<void> {
    await this.applyForStudentMembership(this.duplicateEmailMemberPersonalData);
    await this.assertion.elementVisible(this.errorApplyMemberMessage);
  }

  public async applyStudentMembershipWithoutPhoneEmail(): Promise<void> {
    return await this.applyForMembershipWithConditions(this.personalData, false, false, false);
  }

  public async applyStudentMembershipWithoutPhone(): Promise<void> {
    return await this.applyForMembershipWithConditions(this.personalData, false, false, true);
  }

  public async applyStudentMembershipWithoutEmail(): Promise<void> {
    return await this.applyForMembershipWithConditions(this.personalData, false, true, false);
  }

  public async buyCasualPassWithEmptyPackage(): Promise<void> {
    await this.clickCasualTab();
    await this.casualPersonalDetails(this.personalData);

    await this.clickApplyCasualAccess();
  }

  public async buyRegularCasualPassWithEmptyPersonalDetails(quantity: number = 1): Promise<void> {
    await this.clickCasualTab();
    await this.chooseCasualPackage(this.targetTypeRegular, quantity);

    await this.clickApplyCasualAccess();
  }

  public async buyRegularCasualWithEmptyPhoneAndEmail(quantity: number = 1): Promise<void> {
    await this.clickCasualTab();
    await this.chooseCasualPackage(this.targetTypeRegular, quantity);
    await this.casualPersonalDetails(this.personalData, false, false);

    await this.clickApplyCasualAccess();
  }

  public async buyRegularCasualWithEmptyPhone(quantity: number = 1): Promise<void> {
    await this.clickCasualTab();
    await this.chooseCasualPackage(this.targetTypeRegular, quantity);
    await this.casualPersonalDetails(this.personalData, false, true);

    await this.clickApplyCasualAccess();
  }

  public async buyRegularCasualWithEmptyEmail(quantity: number = 1): Promise<void> {
    await this.clickCasualTab();
    await this.chooseCasualPackage(this.targetTypeRegular, quantity);
    await this.casualPersonalDetails(this.personalData, true, false);

    await this.clickApplyCasualAccess();
  }

  public async buyRegularCasualPass(quantity: number = 1): Promise<void> {
    await this.clickCasualTab();
    await this.chooseCasualPackage(this.targetTypeRegular, quantity);
    await this.casualPersonalDetails(this.personalData);
    await this.clickApplyCasualAccess();
  }

  public async buyRegularCasualPassWithModiyingQuantity(quantity: number): Promise<void> {
    await this.clickCasualTab();
    await this.chooseCasualPackage(this.targetTypeRegular, quantity);
    await this.casualPersonalDetails(this.personalData);

    await this.clickApplyCasualAccess();
  }

  /*
    Student Casual Pass
    */
  public async buyStudentCasualPassWithEmptyPersonalDetails(quantity: number = 1): Promise<void> {
    await this.clickCasualTab();
    await this.chooseCasualPackage(this.targetTypeStudent, quantity);

    await this.clickApplyCasualAccess();
  }

  public async buyStudentCasualPassWithEmptyPhoneAndEmail(quantity: number = 1): Promise<void> {
    await this.clickCasualTab();
    await this.chooseCasualPackage(this.targetTypeStudent, quantity);
    await this.casualPersonalDetails(this.personalData, false, false);

    await this.clickApplyCasualAccess();
  }

  public async buyStudentCasualPassWithEmptyPhone(quantity: number = 1): Promise<void> {
    await this.clickCasualTab();
    await this.chooseCasualPackage(this.targetTypeStudent, quantity);
    await this.casualPersonalDetails(this.personalData, false, true);

    await this.clickApplyCasualAccess();
  }

  public async buyStudentCasualPassWithEmptyEmail(quantity: number = 1): Promise<void> {
    await this.clickCasualTab();
    await this.chooseCasualPackage(this.targetTypeStudent, quantity);
    await this.casualPersonalDetails(this.personalData, true, false);

    await this.clickApplyCasualAccess();
  }

  public async buyStudentCasualPass(quantity: number = 1): Promise<void> {
    await this.clickCasualTab();
    await this.chooseCasualPackage(this.targetTypeStudent, quantity);
    await this.casualPersonalDetails(this.personalData);

    await this.clickApplyCasualAccess();
  }

  public async buyStudentCasualPassWithModifyingQuantity(quantity: number): Promise<void> {
    await this.clickCasualTab();
    await this.chooseCasualPackage(this.targetTypeStudent, quantity);
    await this.casualPersonalDetails(this.personalData);

    await this.clickApplyCasualAccess();
  }

  public async clearMemberDataTest(): Promise<APIResponse> {
    return await this.membershipRequestApiUtils.clearMemberTestData(
      this.personalData.fullname,
      this.personalData.phone,
      this.personalData.email,
    );
  }

  public async clearCasualDataTest(): Promise<APIResponse> {
    return await this.membershipRequestApiUtils.clearCasualTestData(
      this.personalData.fullname,
      this.personalData.phone,
      this.personalData.email,
    );
  }

  public async applyForRegularMembershipByApi(): Promise<void> {
    const formData: MembershipFormDataType = DataProvider.getApplyRegularMembershipFormData();
    await this.membershipRequestApiUtils.registerMembership(formData);
  }

  public async applyForStudentMembershipByApi(): Promise<void> {
    const formData: MembershipFormDataType = DataProvider.getApplyStudentMembershipFormData();
    await this.membershipRequestApiUtils.registerMembership(formData);
  }
}
