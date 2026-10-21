import { APIRequestContext, APIResponse } from '@playwright/test';
import { BaseApiUtils } from './base.api.utils';
import {
  ApiMembershipPlanDataType,
  ApiMembershipPlanDetailDataType,
} from '#types/api-response/api.membership-plan.type.js';
import { ApiCreateMembershipPlanBodyType } from '#types/api-json/api.create.membership-plan.type.js';

export class MembershipPlanApiUtils extends BaseApiUtils {
  private readonly findMembershipPlanByCode = MembershipPlanApiUtils.defineEndpoint({
    description: 'Find Membership Plan by Code',
    // url: /api/v1/admin/membership-plan/:id/code
    url: '/api/v1/admin/membership-plan',
    method: 'GET',
    bodyType: 'none',
  });

  private readonly findMembershipPlanById = MembershipPlanApiUtils.defineEndpoint({
    description: 'Find Active Membership Plan by ID',
    // url: '/api/v1/admin/membership-plan/:id/active
    url: '/api/v1/admin/membership-plan',
    method: 'GET',
    bodyType: 'none',
  });

  private readonly deleteMembershipPlan = MembershipPlanApiUtils.defineEndpoint({
    description: 'delete membership plan by id',
    // url : api/v1/admin/membership-plan/:id
    url: 'api/v1/admin/membership-plan',
    method: 'DELETE',
    bodyType: 'none',
  });

  private readonly createMembershipPlan = MembershipPlanApiUtils.defineEndpoint({
    description: 'create membership plan',
    url: 'api/v1/admin/membership-plan',
    method: 'POST',
    bodyType: 'json',
  });

  // private readonly data: PlanDataType = DataProvider.getAddMembershipPlanData
  constructor(request: APIRequestContext) {
    super(request);
  }

  public async getPlanByCode(code: string): Promise<ApiMembershipPlanDetailDataType> {
    const response: APIResponse = await this.getWithoutBody(
      this.withUrl(this.findMembershipPlanByCode, code, 'code'),
    );
    const body: ApiMembershipPlanDataType = await response.json();

    return body.data;
  }

  public async getActivePlanByID(id: string): Promise<ApiMembershipPlanDetailDataType> {
    const response: APIResponse = await this.getWithoutBody(
      this.withUrl(this.findMembershipPlanById, id),
    );
    const body: ApiMembershipPlanDataType = await response.json();

    return body.data;
  }

  public async deletePlanByCode(code: string): Promise<APIResponse> {
    const plan: ApiMembershipPlanDetailDataType = await this.getPlanByCode(code);

    if (!plan?.id) {
      return null;
    }

    return await this.delete(this.withUrl(this.deleteMembershipPlan, String(plan.id)));
  }

  public async createPlan(data: ApiCreateMembershipPlanBodyType): Promise<APIResponse> {
    return await this.postJson(this.createMembershipPlan, data);
  }
}
