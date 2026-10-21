import { APIRequestContext, APIResponse } from '@playwright/test';
import { BaseApiUtils } from './base.api.utils';
import { PersonalDataType } from '#types/personal.type.js';

export default class MembershipApiUtils extends BaseApiUtils {
  private readonly createMemberWithoutMembershipEndpoint = MembershipApiUtils.defineEndpoint({
    description:
      'To create a member data without membership, membership requests, and transactions',
    url: 'testing/create-member',
    method: 'POST',
    bodyType: 'json',
  });

  constructor(request: APIRequestContext) {
    super(request);
  }

  public async createMemberWithoutMembership(
    data: PersonalDataType,
    regular: boolean,
  ): Promise<APIResponse> {
    const inputData = {
      ...data,
      member_type: regular ? 'REGULAR' : 'STUDENT',
    };
    return await this.postJson(this.createMemberWithoutMembershipEndpoint, inputData);
  }
}
