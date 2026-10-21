import { Page } from '@playwright/test';

export default class TextUtils {
  public static getTokenFromURL(page: Page): string {
    const url = page.url();
    const token = new URL(url).searchParams.get('token');
    if (!token) throw new Error(`Token not found in URL: ${url}`);

    return token;
  }
}
