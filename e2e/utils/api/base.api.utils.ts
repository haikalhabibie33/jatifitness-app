import { ApiEndpointType } from '#types/api.data.type.js';
import { LoginInputType } from '#types/login.type.js';
import DataProvider from '#utils/data.provider.utils.js';
import { APIRequestContext, APIResponse } from '@playwright/test';

export class BaseApiUtils {
  protected readonly request: APIRequestContext;

  protected static defineEndpoint<T extends ApiEndpointType>(endpoint: T): T {
    return endpoint;
  }

  private readonly loginData: LoginInputType = DataProvider.getLoginData().validUser;

  private isAuthenticated: boolean = false;

  private readonly clearMemberTest = BaseApiUtils.defineEndpoint({
    description:
      'To clear all data of member test (membership requets, transactions, history transactions, memberships, member, member access)',
    url: '/testing/member/clear-data',
    method: 'POST',
    bodyType: 'json',
  });

  private readonly clearCasualTest = BaseApiUtils.defineEndpoint({
    description:
      'To clear all data of casual test (membership requets, transactions, history transactions, casual passes)',
    url: '/testing/casual/clear-data',
    method: 'POST',
    bodyType: 'json',
  });

  constructor(request: APIRequestContext) {
    this.request = request;
  }

  public async authenticate(): Promise<void> {
    if (this.isAuthenticated) {
      return
    }

    const loginData = {
        username: this.loginData.username,
        password: this.loginData.password,
      };

    const loginResponse = await this.request.post('/login', { form: loginData });

    if (!loginResponse.ok())  {
      throw new Error(`Admin login failed with status ${loginResponse.status()}`);
    }

    const dashbordResponse = await this.request.get('/admin/dashboard');
    const dashboardUrl = dashbordResponse.url();

    if (dashboardUrl.includes('/login')) {
      throw new Error('Admin authentication failed. Request was redirected to login page');
    }
    
    this.isAuthenticated = true;
  }

  protected async postWithoutBody(
    endpoint: ApiEndpointType & { method: 'POST'; bodyType: 'none' },
  ): Promise<APIResponse> {
    await this.authenticate();
    return await this.request.post(endpoint.url);
  }

  protected async postJson(
    endpoint: ApiEndpointType & { method: 'POST'; bodyType: 'json' },
    data: object,
  ): Promise<APIResponse> {
    await this.authenticate();
    return await this.request.post(endpoint.url, { data });
  }

  protected async postForm(
    endpoint: ApiEndpointType & { method: 'POST'; bodyType: 'form' },
    form: Record<string, string | number | boolean>,
  ): Promise<APIResponse> {
    await this.authenticate();
    return await this.request.post(endpoint.url, { form });
  }

  protected async getWithoutBody(
    endpoint: ApiEndpointType & { method: 'GET'; bodyType: 'none' },
  ): Promise<APIResponse> {
    await this.authenticate();
    return await this.request.get(endpoint.url);
  }

  protected async getWithParams(
    endpoint: ApiEndpointType & { method: 'GET'; bodyType: 'params' },
    params: Record<string, string | number | boolean>,
  ): Promise<APIResponse> {
    await this.authenticate();
    return await this.request.get(endpoint.url, { params });
  }

  protected async getWithJson(
    endpoint: ApiEndpointType & { method: 'GET'; bodyType: 'json' },
    data: object,
  ): Promise<APIResponse> {
    await this.authenticate();
    return await this.request.get(endpoint.url, { data });
  }

  protected async delete(
    endpoint: ApiEndpointType & { method: 'DELETE'; bodyType: 'none' },
  ): Promise<APIResponse> {
    await this.authenticate();
    return await this.request.delete(endpoint.url);
  }

  protected withUrl<T extends ApiEndpointType>(
    endpoint: T,
    ...segments: Array<string | number>
  ): T {
    return {
      ...endpoint,
      url: `${endpoint.url}/${segments.map(String).join('/')}`,
    };
  }

  public async clearMemberTestData(
    name: string,
    phone: string,
    email: string,
  ): Promise<APIResponse> {
    return await this.postJson(this.clearMemberTest, { name, phone, email });
  }

  public async clearCasualTestData(
    name: string,
    phone: string,
    email: string,
  ): Promise<APIResponse> {
    return await this.postJson(this.clearCasualTest, { name, phone, email });
  }
}
