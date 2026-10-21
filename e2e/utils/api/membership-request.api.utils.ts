import { APIRequestContext, APIResponse } from '@playwright/test';
import { BaseApiUtils } from './base.api.utils';
import { MembershipFormDataType } from '#types/api-form/apply.membership.type.js';
import { ApiMembershipRequestType } from '#types/api-form/membership-requests.type.js';

type IdAndToken = {
  id: string;
  token: string;
};

export class MembershipRequestApiUtils extends BaseApiUtils {
  private readonly membershipRequestByPhoneEmail = MembershipRequestApiUtils.defineEndpoint({
    description: 'To get membership requests row by inputting phone and email',
    url: '/testing/membership-requests/phone-email',
    method: 'GET',
    bodyType: 'json',
  });

  private readonly approveStudentRequest = MembershipRequestApiUtils.defineEndpoint({
    description:
      'To approve any membership request of student, to change SUBMITTED to APPROVED in database',
    url: '/admin/membership-request/approve',
    method: 'POST',
    bodyType: 'none',
  });

  private readonly registerMembershipRequest = MembershipRequestApiUtils.defineEndpoint({
    description: 'To register membership using App service',
    url: '/membership/submit-member',
    method: 'POST',
    bodyType: 'form',
  });

  constructor(request: APIRequestContext) {
    super(request);
  }

  public async getMembershipRequestIdToken(
    phone: string,
    email: string,
  ): Promise<IdAndToken | null> {
    const response = await this.getWithJson(this.membershipRequestByPhoneEmail, { phone, email });
    const body: ApiMembershipRequestType = await response.json();
    return {
      id: body.requests[0].id,
      token: body.requests[0].access_token,
    } as IdAndToken;
  }

  public async approveStudentByMembershipRequestId(
    phone: string,
    email: string,
  ): Promise<APIResponse> {
    await this.authenticate();

    const requestId: IdAndToken = await this.getMembershipRequestIdToken(phone, email);
    if (requestId.id === null) throw new Error('Failed to approve student membership');

    const id = Number(requestId.id);
    return await this.postWithoutBody(this.withUrl(this.approveStudentRequest, id));
  }

  public async registerMembership(formData: MembershipFormDataType): Promise<APIResponse> {
    if (!formData) throw new Error('Failed to get membership form data');
    return await this.postForm(this.registerMembershipRequest, formData);
  }
}
