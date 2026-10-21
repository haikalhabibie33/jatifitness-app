import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import { CasualPackageDataType } from '#types/page/member.type.js';
import { PersonalDataType } from '#types/personal.type.js';
import DataProvider from '#utils/data.provider.utils.js';
import { APIRequestContext, Page } from '@playwright/test';

export default class BuyCasualPassModalPage extends BasePage {
  private readonly modalTitle: LocatorType;
  private readonly casualPersonalCategory: LocatorType;
  private readonly casualPackage: LocatorType;
  private readonly casualQuantity: LocatorType;
  private readonly casualTotalDuration: LocatorType;
  private readonly casualTotalPrice: LocatorType;
  private readonly casualFullname: LocatorType;
  private readonly casualPhoneNumber: LocatorType;
  private readonly casualEmailAddress: LocatorType;
  private readonly casualCreateButton: LocatorType;
  private readonly casualCancelButton: LocatorType;

  private readonly regular: boolean = true;
  private readonly student: boolean = false;

  private readonly personalDetails: PersonalDataType;

  private readonly regularCasualPackage: CasualPackageDataType;
  private readonly studentCasualPackage: CasualPackageDataType;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.modalTitle = { description: '', value: 'casual-modal-title' };
    this.casualPersonalCategory = { description: '', value: 'casual-personal-category' };
    this.casualPackage = { description: '', value: 'casual-package' };
    this.casualQuantity = { description: '', value: 'casual-quantity' };
    this.casualTotalDuration = { description: '', value: 'casual-total-duration' };
    this.casualTotalPrice = { description: '', value: 'casual-total-price' };
    this.casualFullname = { description: '', value: 'casual-fullname' };
    this.casualPhoneNumber = { description: '', value: 'casual-phone' };
    this.casualEmailAddress = { description: '', value: 'casual-email' };
    this.casualCreateButton = { description: '', value: 'casual-create' };
    this.casualCancelButton = { description: '', value: 'casual-cancel' };

    this.personalDetails = DataProvider.getPersonalDetails();
    this.regularCasualPackage = DataProvider.regularCasualPackage();
    this.studentCasualPackage = DataProvider.studentCasualPackage();
  }

  private async selectPersonalCategory(category: string): Promise<void> {
    await this.elements.selectByValue(this.casualPersonalCategory, category);
    await this.assertion.expectToHaveValue(this.casualPersonalCategory, category);
  }

  private async selectCasualPackage(packages: string): Promise<void> {
    // await this.elements.selectByLabel(this.casualPackage, packages);
    // await this.assertion.expectToHaveValue(this.casualPackage, packages);
    await this.elements.selectByCertainAttribute(this.casualPackage, 'data-name', packages);
  }

  private async fillQuantity(quantity: number): Promise<void> {
    await this.elements.fillElement(this.casualQuantity, String(quantity));
    await this.assertion.expectToHaveValue(this.casualQuantity, String(quantity));
  }

  private async assertTotalDuration(expectedDuration: number): Promise<void> {
    const actualDuration = await this.elements.getElementValue(this.casualTotalDuration);
    this.assertion.expectStringToEqual(actualDuration, String(expectedDuration));
  }

  private async assertTotalPrice(expectedPrice: string): Promise<void> {
    const actualPrice = await this.elements.getElementValue(this.casualTotalPrice);
    this.assertion.expectStringToEqual(actualPrice, expectedPrice);
  }

  private async fillFullname(fullname: string): Promise<void> {
    await this.elements.fillElement(this.casualFullname, fullname);
    await this.assertion.expectToHaveValue(this.casualFullname, fullname);
  }

  private async fillPhoneNumber(phone: string): Promise<void> {
    await this.elements.fillElement(this.casualPhoneNumber, phone);
    await this.assertion.expectToHaveValue(this.casualPhoneNumber, phone);
  }

  private async fillEmailAddress(email: string): Promise<void> {
    await this.elements.fillElement(this.casualEmailAddress, email);
    await this.assertion.expectToHaveValue(this.casualEmailAddress, email);
  }

  private async createCasualPass(regular: boolean, quantity: number): Promise<void> {
    await this.assertion.elementVisible(this.modalTitle);

    await this.selectPersonalCategory(
      regular
        ? this.regularCasualPackage.personal_category
        : this.studentCasualPackage.personal_category,
    );

    await this.selectCasualPackage(
      regular ? this.regularCasualPackage.casual_package : this.studentCasualPackage.casual_package,
    );

    await this.fillQuantity(quantity);
    await this.assertTotalDuration(quantity);

    const price = regular ? this.regularCasualPackage.price : this.studentCasualPackage.price;

    const tempPrice = Number(price) * quantity;
    const fixPrice = tempPrice.toLocaleString('ID-id');
    await this.assertTotalPrice(String(`Rp ${fixPrice}`));

    await this.fillFullname(this.personalDetails.fullname);
    await this.fillPhoneNumber(this.personalDetails.phone);
    await this.fillEmailAddress(this.personalDetails.email);

    await this.elements.clickElement(this.casualCreateButton);
  }

  public async createRegularCasualPass(quantity: number = 1): Promise<void> {
    return this.createCasualPass(this.regular, quantity);
  }

  public async createStudentCasualPass(quantity: number = 1): Promise<void> {
    return this.createCasualPass(this.student, quantity);
  }
}
