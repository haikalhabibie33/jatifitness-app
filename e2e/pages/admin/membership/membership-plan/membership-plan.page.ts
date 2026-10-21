import { BasePage } from '#pages/base.page.js';
import { LocatorType } from '#types/locator.js';
import TableUtils from '#utils/table.utils.js';
import { APIRequestContext, Page } from '@playwright/test';
import { MembershipPlanTableConfig } from './membership-plan.table';
import { PlanDataType } from '#types/membership-plan.data.type.js';
import DataProvider from '#utils/data.provider.utils.js';
import { AUDIENCE_SCOPE, TARGET_MEMBER } from '#utils/enum.utils.js';
import { MembershipPlanApiUtils } from '#utils/api/membership-plan.api.utils.js';

export default class MembershipPlanModalPage extends BasePage {
  private readonly addPlanButton: LocatorType;
  private membershipPlanApiUtils: MembershipPlanApiUtils;
  private addPlanData: PlanDataType = DataProvider.getAddMembershipPlanData();
  private editPlanData: PlanDataType = DataProvider.getEditMembershipPlanData();

  private readonly membershipPlanTable: TableUtils<
    typeof MembershipPlanTableConfig.columns,
    typeof MembershipPlanTableConfig.actions
  >;

  constructor(page: Page, request: APIRequestContext) {
    super(page, request);
    this.membershipPlanTable = new TableUtils(page, MembershipPlanTableConfig);
    this.addPlanButton = { value: 'add-plan-btn', description: 'Create new plan button' };
    this.membershipPlanApiUtils = new MembershipPlanApiUtils(request);
  }

  public async clickAddPlan(): Promise<void> {
    await this.elements.clickElement(this.addPlanButton);
  }

  public async clickEditPlan(): Promise<void> {
    const code: string = this.addPlanData.code;
    await this.membershipPlanTable.clickAction({ code }, 'editPlan');
  }

  private async assertMembershipPlan(
    targetType: string,
    audienceScope: string,
    newPlan: boolean = true,
    isActive: boolean = true,
  ): Promise<void> {
    const code: string = this.addPlanData.code;
    const rowData = await this.membershipPlanTable.getRowData({ code });
    const data = newPlan ? this.addPlanData : this.editPlanData;
    const status = isActive ? 'Active' : 'Inactive';

    const formattedPrice = Number(data.price)
      .toLocaleString('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })
      .replace(',', '.');

    this.assertion.expectStringToEqual(rowData.code, data.code);
    this.assertion.expectStringToEqual(rowData.name, data.planName);
    this.assertion.expectStringToEqual(rowData.target, targetType);
    this.assertion.expectStringToEqual(rowData.scope, audienceScope);
    this.assertion.expectStringToEqual(rowData.duration, `${data.duration} days`);
    this.assertion.expectStringToEqual(rowData.price, `Rp ${formattedPrice}`);
    this.assertion.expectStringToEqual(rowData.dailyLimit, `${data.dailyLimit} x day`);
    this.assertion.expectStringToEqual(rowData.status, status);
  }

  public async assertNewRegularMemberPlan(): Promise<void> {
    await this.assertMembershipPlan(TARGET_MEMBER.REGULAR, AUDIENCE_SCOPE.MEMBER_ONLY);
  }

  public async assertNewStudentMemberPlan(): Promise<void> {
    await this.assertMembershipPlan(TARGET_MEMBER.STUDENT, AUDIENCE_SCOPE.MEMBER_ONLY);
  }

  public async assertNewRegularCasualPlan(): Promise<void> {
    await this.assertMembershipPlan(TARGET_MEMBER.REGULAR, AUDIENCE_SCOPE.CASUAL_ONLY);
  }

  public async assertNewStudentCasualPlan(): Promise<void> {
    await this.assertMembershipPlan(TARGET_MEMBER.STUDENT, AUDIENCE_SCOPE.CASUAL_ONLY);
  }

  public async assertNewAllMemberPlan(): Promise<void> {
    await this.assertMembershipPlan(TARGET_MEMBER.ALL, AUDIENCE_SCOPE.MEMBER_ONLY);
  }

  public async assertNewAllCasualPlan(): Promise<void> {
    await this.assertMembershipPlan(TARGET_MEMBER.ALL, AUDIENCE_SCOPE.CASUAL_ONLY);
  }

  public async assertNewForAllPlan(): Promise<void> {
    await this.assertMembershipPlan(TARGET_MEMBER.ALL, AUDIENCE_SCOPE.BOTH);
  }

  public async assertEditedRegularMemberPlan(): Promise<void> {
    await this.assertMembershipPlan(TARGET_MEMBER.REGULAR, AUDIENCE_SCOPE.MEMBER_ONLY, false);
  }

  public async assertEditedStudentMemberPlan(): Promise<void> {
    await this.assertMembershipPlan(TARGET_MEMBER.STUDENT, AUDIENCE_SCOPE.MEMBER_ONLY, false);
  }

  public async assertEditedRegularCasualPlan(): Promise<void> {
    await this.assertMembershipPlan(TARGET_MEMBER.REGULAR, AUDIENCE_SCOPE.CASUAL_ONLY, false);
  }

  public async assertEditedStudentCasualPlan(): Promise<void> {
    await this.assertMembershipPlan(TARGET_MEMBER.STUDENT, AUDIENCE_SCOPE.CASUAL_ONLY, false);
  }

  // API utils
  public async clearMembershipPlanTestData(): Promise<void> {
    await this.membershipPlanApiUtils.deletePlanByCode(this.addPlanData.code);
  }

  public async createRegularMembershipPlanApi(): Promise<void> {
    const data = {
      ...this.addPlanData,
      target_member_type: TARGET_MEMBER.REGULAR,
      audience_scope: 'MEMBER_ONLY',
      name: this.addPlanData.planName,
      duration_days: Number(this.addPlanData.duration),
      daily_limit: Number(this.addPlanData.dailyLimit),
      is_active: 1,
      price: Number(this.addPlanData.price),
    };
    await this.membershipPlanApiUtils.createPlan(data);
    const plan = await this.membershipPlanApiUtils.getPlanByCode(this.addPlanData.code);
    console.log(` Plan ID in DB is: ${plan?.id}`);
  }

  public async createStudentMembershipPlanApi(): Promise<void> {
    const data = {
      ...this.addPlanData,
      target_member_type: TARGET_MEMBER.STUDENT,
      audience_scope: AUDIENCE_SCOPE.MEMBER_ONLY,
      name: this.addPlanData.planName,
      duration_days: Number(this.addPlanData.duration),
      daily_limit: Number(this.addPlanData.dailyLimit),
      is_active: 1,
      price: Number(this.addPlanData.price),
    };
    await this.membershipPlanApiUtils.createPlan(data);
  }

  public async createRegularCasualPlanApi(): Promise<void> {
    const data = {
      ...this.addPlanData,
      target_member_type: TARGET_MEMBER.REGULAR,
      audience_scope: AUDIENCE_SCOPE.CASUAL_ONLY,
      name: this.addPlanData.planName,
      duration_days: Number(this.addPlanData.duration),
      daily_limit: Number(this.addPlanData.dailyLimit),
      is_active: 1,
      price: Number(this.addPlanData.price),
    };
    await this.membershipPlanApiUtils.createPlan(data);
  }

  public async createStudentCasualPlanApi(): Promise<void> {
    const data = {
      ...this.addPlanData,
      target_member_type: TARGET_MEMBER.STUDENT,
      audience_scope: AUDIENCE_SCOPE.CASUAL_ONLY,
      name: this.addPlanData.planName,
      duration_days: Number(this.addPlanData.duration),
      daily_limit: Number(this.addPlanData.dailyLimit),
      is_active: 1,
      price: Number(this.addPlanData.price),
    };
    await this.membershipPlanApiUtils.createPlan(data);
  }
}
