import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import { PlanDataType } from '#types/membership-plan.data.type.js';
import DataProvider from '#utils/data.provider.utils.js';
import { AUDIENCE_SCOPE, TARGET_MEMBER } from '#utils/enum.utils.js';
import { APIRequestContext, Page } from '@playwright/test';

export default class AddMembershipPlanModalPage extends BasePage {
  private readonly modalTitle: LocatorType;
  private readonly code: LocatorType;
  private readonly planName: LocatorType;
  private readonly targetMember: LocatorType;
  private readonly audienceScope: LocatorType;
  private readonly duration: LocatorType;
  private readonly price: LocatorType;
  private readonly dailyLimit: LocatorType;
  private readonly description: LocatorType;

  private readonly cancelButton: LocatorType;
  private readonly SaveButton: LocatorType;

  private readonly addPlanData: PlanDataType = DataProvider.getAddMembershipPlanData();

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.modalTitle = { value: 'add-membership-plan-modal-title', description: '' };
    this.code = { value: 'add-membership-plan-code', description: '' };
    this.planName = { value: 'add-membership-plan-plan-name', description: '' };
    this.targetMember = { value: 'add-membership-plan-target-member', description: '' };
    this.audienceScope = { value: 'add-membership-plan-audience-scope', description: '' };
    this.duration = { value: 'add-membership-plan-duration', description: '' };
    this.price = { value: 'add-membership-plan-price', description: '' };
    this.dailyLimit = { value: 'add-membership-plan-daily-limit', description: '' };
    this.description = { value: 'add-membership-plan-description', description: '' };
    this.cancelButton = { value: 'add-membership-plan-cancel-button', description: '' };
    this.SaveButton = { value: 'add-membership-plan-save-button', description: '' };
  }

  private async fillCode(code: string): Promise<void> {
    await this.elements.fillElement(this.code, code);
  }

  private async fillPlanName(name: string): Promise<void> {
    await this.elements.fillElement(this.planName, name);
  }

  private async selectTargetMember(target: string): Promise<void> {
    await this.elements.selectByValue(this.targetMember, target);
  }

  private async selectAudienceScope(audience: string): Promise<void> {
    await this.elements.selectByLabel(this.audienceScope, audience);
  }

  private async fillDuration(duration: string): Promise<void> {
    await this.elements.fillElement(this.duration, duration);
  }

  private async fillPrice(price: string): Promise<void> {
    await this.elements.fillElement(this.price, price);
  }

  private async fillDailyLimit(daily: string): Promise<void> {
    await this.elements.fillElement(this.dailyLimit, daily);
  }

  private async fillDescription(description: string): Promise<void> {
    await this.elements.fillElement(this.description, description);
  }

  private async clickCancelButton(): Promise<void> {
    await this.elements.clickElement(this.cancelButton);
  }

  private async clickSaveButton(): Promise<void> {
    await this.elements.clickElement(this.SaveButton);
  }

  private async createPlan(
    data: PlanDataType,
    targetMember: string,
    audienceScope: string,
  ): Promise<void> {
    await this.assertion.elementVisible(this.modalTitle);

    await this.fillCode(data.code);
    await this.fillPlanName(data.planName);
    await this.selectTargetMember(targetMember);
    await this.selectAudienceScope(audienceScope);
    await this.fillDuration(data.duration);
    await this.fillPrice(data.price);
    await this.fillDailyLimit(data.dailyLimit);
    await this.fillDescription(data.description);

    await this.clickSaveButton();
    await this.assertion.elementNotVisible(this.modalTitle);
  }

  public async createRegularMembershipPlan(): Promise<void> {
    await this.createPlan(this.addPlanData, TARGET_MEMBER.REGULAR, AUDIENCE_SCOPE.MEMBER_ONLY);
  }

  public async createStudentMembershipPlan(): Promise<void> {
    await this.createPlan(this.addPlanData, TARGET_MEMBER.STUDENT, AUDIENCE_SCOPE.MEMBER_ONLY);
  }

  public async createRegularCasualPlan(): Promise<void> {
    await this.createPlan(this.addPlanData, TARGET_MEMBER.REGULAR, AUDIENCE_SCOPE.CASUAL_ONLY);
  }

  public async createStudentCasualPlan(): Promise<void> {
    await this.createPlan(this.addPlanData, TARGET_MEMBER.STUDENT, AUDIENCE_SCOPE.CASUAL_ONLY);
  }

  public async createAllMemberPlan(): Promise<void> {
    await this.createPlan(this.addPlanData, TARGET_MEMBER.ALL, AUDIENCE_SCOPE.CASUAL_ONLY);
  }

  public async createAllCasualPlan(): Promise<void> {
    await this.createPlan(this.addPlanData, TARGET_MEMBER.ALL, AUDIENCE_SCOPE.CASUAL_ONLY);
  }

  public async createForAllPlan(): Promise<void> {
    await this.createPlan(this.addPlanData, TARGET_MEMBER.ALL, AUDIENCE_SCOPE.BOTH);
  }
}
