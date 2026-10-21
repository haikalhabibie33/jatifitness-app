import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import { PersonalDataType } from '#types/personal.type.js';
import DataProvider from '#utils/data.provider.utils.js';
import TextUtils from '#utils/text.utils.js';
import { APIRequestContext, Page } from '@playwright/test';

export default class AccessQrPage extends BasePage {
  private readonly accessModeName: LocatorType;
  private readonly accessModeInformation: LocatorType;
  private readonly qrCodeAccess: LocatorType;
  private readonly accessToken: LocatorType;
  private readonly downloadQrCode: LocatorType;
  private readonly backToHomeBtn: LocatorType;
  private readonly membershipPlanName: LocatorType;
  private readonly membershipDuration: LocatorType;
  private readonly accessCodeNotFound: LocatorType;
  private readonly accessQrPageData = DataProvider.getAccessQrPageData();

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.accessModeName = {
      description: 'Different message if it is casual or member',
      value: 'access-mode-name',
    };
    this.accessModeInformation = {
      description: 'Different message if it is casual or member',
      value: 'access-mode-information',
    };
    this.qrCodeAccess = {
      description: 'Generated QR Code by js in the page to enter the gym',
      value: 'qrCode',
    };
    this.accessToken = {
      description: 'An access token to enter gym facility',
      value: 'access-token',
    };
    this.downloadQrCode = {
      description: 'A download button for QR Code image',
      value: 'download-qr-btn',
    };
    this.backToHomeBtn = {
      description: 'Back to home button when QR Access is printed already',
      value: 'back-to-home',
    };
    this.membershipPlanName = {
      description: 'Purchased membership plan name that printed in the page',
      value: 'membership-plan-name',
    };
    this.membershipDuration = {
      description: 'Purchased membership duration that printed in the page',
      value: 'membership-duration',
    };
    this.accessCodeNotFound = {
      description: 'a message when QR code or access token is not found',
      value: 'access-code-not-found',
    };
  }

  private async checkQrAndTokenExist(): Promise<void> {
    await this.assertion.elementVisible(this.qrCodeAccess);
    await this.assertion.elementVisible(this.accessToken);
  }

  private async checkMembershipPlanAndDurationExist(): Promise<void> {
    await this.assertion.elementVisible(this.membershipPlanName);
    await this.assertion.elementVisible(this.membershipDuration);
  }

  private async assertTokenExistAndCorrect(): Promise<void> {
    const data: PersonalDataType = DataProvider.getPersonalDetails();

    const tokenFromUrl = TextUtils.getTokenFromURL(this.page);
    const tokenFromApi = await this.membershipRequestApiUtils.getMembershipRequestIdToken(
      data.phone,
      data.email,
    );

    return this.assertion.expectStringToEqual(tokenFromApi.token, tokenFromUrl);
  }

  public async checkFinalResultCasual(): Promise<void> {
    await this.checkQrAndTokenExist();
    await this.checkMembershipPlanAndDurationExist();

    await this.assertion.expectToHaveText(
      this.accessModeName,
      this.accessQrPageData.accessCodeName.casual,
    );

    await this.assertion.expectToHaveText(
      this.accessModeInformation,
      this.accessQrPageData.accessCodeInformation.casual,
    );

    await this.assertTokenExistAndCorrect();
  }

  public async checkFinalResultMember(): Promise<void> {
    await this.checkQrAndTokenExist();
    await this.checkMembershipPlanAndDurationExist();

    await this.assertion.expectToHaveText(
      this.accessModeName,
      this.accessQrPageData.accessCodeName.member,
    );

    await this.assertion.expectToHaveText(
      this.accessModeInformation,
      this.accessQrPageData.accessCodeInformation.member,
    );

    await this.assertTokenExistAndCorrect();
  }
}
