import { APIRequestContext, APIResponse } from '@playwright/test';
import { BaseApiUtils } from './base.api.utils';

export default class PaymentApiUtils extends BaseApiUtils {
  private readonly adminPaymentRequest = PaymentApiUtils.defineEndpoint({
    description: 'To pay incomplete transaction through admin to proceed a membership/ casual pass',
    url: '/admin/payment/confirm',
    method: 'POST',
    bodyType: 'form',
  });

  constructor(request: APIRequestContext) {
    super(request);
  }

  public async paymentByAdminApi(id: number, token: string): Promise<APIResponse> {
    await this.authenticate();

    const form = {
      request_id: id,
      token: token,
      source: 'OFFLINE',
    };

    return await this.postForm(this.adminPaymentRequest, form);
  }
}
