import { APIRequestContext, Page } from '@playwright/test';
import Assertions from '../utils/assertion.utils';
import Elements from '../utils/element.utils';
import PaymentApiUtils from '#utils/api/payment.api.utils';
import { Response } from '@playwright/test';
import { MembershipRequestApiUtils } from '#utils/api/membership-request.api.utils.js';
import DateUtils from '#utils/date.utils.js';
import ElementDebugUtils from '#utils/element.debug.utils.js';

export class BasePage {
  protected readonly page: Page;
  protected readonly request: APIRequestContext;

  protected readonly elements: Elements;
  protected readonly debugElements: ElementDebugUtils;
  protected readonly assertion: Assertions;
  protected readonly paymentApiUtils: PaymentApiUtils;
  protected readonly dateUtils: DateUtils;
  protected readonly membershipRequestApiUtils: MembershipRequestApiUtils;

  constructor(page: Page, request: APIRequestContext) {
    this.page = page;
    this.request = request;

    this.elements = new Elements(page);
    this.debugElements = new ElementDebugUtils(page);
    this.assertion = new Assertions(page);
    this.paymentApiUtils = new PaymentApiUtils(request);
    this.dateUtils = new DateUtils();
    this.membershipRequestApiUtils = new MembershipRequestApiUtils(request);
  }

  public async reloadPage(): Promise<Response | null> {
    return await this.page.reload({
      timeout: 20000,
      waitUntil: 'networkidle',
    });
  }
}
