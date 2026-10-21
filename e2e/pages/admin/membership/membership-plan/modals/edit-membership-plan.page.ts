import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import { PlanDataType } from '#types/membership-plan.data.type.js';
import DataProvider from '#utils/data.provider.utils.js';
import { AUDIENCE_SCOPE, TARGET_MEMBER } from '#utils/enum.utils.js';
import { APIRequestContext, Page } from '@playwright/test';

export default class EditMembershipPlanModalPage extends BasePage {
  private readonly modalTitle: LocatorType;
  private readonly code: LocatorType;
  private readonly planName: LocatorType;
  private readonly targetMember: LocatorType;
  private readonly audienceScope: LocatorType;
  private readonly duration: LocatorType;
  private readonly price: LocatorType;
  private readonly dailyLimit: LocatorType;
  private readonly description: LocatorType;
  private readonly toggleActive: LocatorType;

  private readonly cancelButton: LocatorType;
  private readonly saveButton: LocatorType;

  private readonly currentData: PlanDataType = DataProvider.getAddMembershipPlanData();
  private readonly newData: PlanDataType = DataProvider.getEditMembershipPlanData();

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.modalTitle = { value: 'edit-membership-plan-modal-title', description: '' };
    this.code = { value: 'edit-membership-plan-code', description: '' };
    this.planName = { value: 'edit-membership-plan-name', description: '' };
    this.targetMember = { value: 'edit-membership-plan-target-member', description: '' };
    this.audienceScope = { value: 'edit-membership-plan-audience-scope', description: '' };
    this.duration = { value: 'edit-membership-plan-duration', description: '' };
    this.price = { value: 'edit-membership-plan-price', description: '' };
    this.dailyLimit = { value: 'edit-membership-plan-daily-limit', description: '' };
    this.description = { value: 'edit-membership-plan-description', description: '' };
    this.toggleActive = { value: 'edit-membership-plan-isactive', description: '' };

    this.cancelButton = { value: 'edit-membership-plan-cancel-button', description: '' };
    this.saveButton = { value: 'edit-membership-plan-save-button', description: '' };
  }

  private async fillCode(code: string): Promise<void> {
    await this.elements.fillElementWithClear(this.code, code);
    await this.assertion.expectToHaveValue(this.code, code);
  }

  private async fillPlanName(planName: string): Promise<void> {
    await this.elements.fillElementWithClear(this.planName, planName);
    await this.assertion.expectToHaveValue(this.planName, planName);
  }

  private async selectTargetMember(target: string): Promise<void> {
    await this.elements.selectByValue(this.targetMember, target);
    await this.assertion.expectToHaveValue(this.targetMember, target);
  }

  private async selectAudienceScope(audience: string): Promise<void> {
    await this.elements.selectByLabel(this.audienceScope, audience);
    await this.assertion.expectToHaveValue(this.audienceScope, audience);
  }

  private async fillPrice(price: string): Promise<void> {
    await this.elements.fillElementWithClear(this.price, price);
    await this.assertion.expectToHaveValue(this.price, price);
  }

  private async fillDurationDays(duration: string): Promise<void> {
    await this.elements.fillElementWithClear(this.duration, duration);
    await this.assertion.expectToHaveValue(this.duration, duration);
  }

  private async fillDailyLimit(limit: string): Promise<void> {
    await this.elements.fillElementWithClear(this.dailyLimit, limit);
    await this.assertion.expectToHaveValue(this.dailyLimit, limit);
  }

  private async fillDescription(description: string): Promise<void> {
    await this.elements.fillElementWithClear(this.description, description);
    await this.assertion.expectToHaveValue(this.description, description);
  }

  private async modifyPlanStatus(active: boolean): Promise<void> {
    if (active) {
      await this.elements.checkInputCheckbox(this.toggleActive);
      await this.assertion.expectCheckboxChecked(this.toggleActive);
    } else {
      await this.elements.uncheckInputCheckbox(this.toggleActive);
      await this.assertion.expectCheckboxUnchecked(this.toggleActive);
    }
  }

  private async getAndAssertMembershipPlan(
    data: PlanDataType,
    targetMemberType: string,
    audience: string,
    isActive: boolean = true,
  ): Promise<void> {
    const code: string = await this.elements.getElementValue(this.code);
    const planName: string = await this.elements.getElementValue(this.planName);
    const targetMember: string = await this.elements.getElementValue(this.targetMember);
    const audienceScope: string = await this.elements.getElementValue(this.audienceScope);
    const price: string = await this.elements.getElementValue(this.price);
    const durationDays: string = await this.elements.getElementValue(this.duration);
    const dailyLimit: string = await this.elements.getElementValue(this.dailyLimit);
    const description: string = await this.elements.getElementValue(this.description);
    // const status: boolean = await this.elements.isCheckboxChecked(this.toggleActive);

    this.assertion.expectStringToEqual(data.code, code);
    this.assertion.expectStringToEqual(data.planName, planName);
    this.assertion.expectStringToEqual(targetMemberType, targetMember);
    this.assertion.expectStringToEqual(audience, audienceScope);
    this.assertion.expectStringToEqual(data.price, price);
    this.assertion.expectStringToEqual(data.duration, durationDays);
    this.assertion.expectStringToEqual(data.dailyLimit, dailyLimit);
    this.assertion.expectStringToEqual(data.description, description);
    if (isActive) {
      await this.assertion.expectCheckboxChecked(this.toggleActive);
    } else {
      await this.assertion.expectCheckboxUnchecked(this.toggleActive);
    }
  }

  private async editMembershipPlan(
    data: PlanDataType,
    targetMemberType: string,
    audience: string,
    isActive: boolean = true,
  ): Promise<void> {
    await this.assertion.elementVisible(this.modalTitle);

    // await this.fillCode(data.code);
    await this.fillPlanName(data.planName);
    await this.selectTargetMember(targetMemberType);
    await this.selectAudienceScope(audience);
    await this.fillPrice(data.price);
    await this.fillDurationDays(data.duration);
    await this.fillDailyLimit(data.dailyLimit);
    await this.fillDescription(data.description);
    await this.modifyPlanStatus(isActive);

    await this.elements.clickElement(this.saveButton);
    await this.assertion.elementNotVisible(this.modalTitle);
  }

  public async editFromStudentIntoRegularMember(): Promise<void> {
    await this.getAndAssertMembershipPlan(
      this.currentData,
      TARGET_MEMBER.STUDENT,
      AUDIENCE_SCOPE.MEMBER_ONLY,
    );

    await this.editMembershipPlan(this.newData, TARGET_MEMBER.REGULAR, AUDIENCE_SCOPE.MEMBER_ONLY);
  }

  public async editFromRegularIntoStudentMember(): Promise<void> {
    await this.getAndAssertMembershipPlan(
      this.currentData,
      TARGET_MEMBER.REGULAR,
      AUDIENCE_SCOPE.MEMBER_ONLY,
    );

    await this.editMembershipPlan(this.newData, TARGET_MEMBER.STUDENT, AUDIENCE_SCOPE.MEMBER_ONLY);
  }

  public async editFromStudentIntoRegularCasual(): Promise<void> {
    await this.getAndAssertMembershipPlan(
      this.currentData,
      TARGET_MEMBER.STUDENT,
      AUDIENCE_SCOPE.CASUAL_ONLY,
    );

    await this.editMembershipPlan(this.newData, TARGET_MEMBER.REGULAR, AUDIENCE_SCOPE.CASUAL_ONLY);
  }

  public async editFromRegularIntoStudentCasual(): Promise<void> {
    await this.getAndAssertMembershipPlan(
      this.currentData,
      TARGET_MEMBER.REGULAR,
      AUDIENCE_SCOPE.CASUAL_ONLY,
    );

    await this.editMembershipPlan(this.newData, TARGET_MEMBER.STUDENT, AUDIENCE_SCOPE.CASUAL_ONLY);
  }
}
