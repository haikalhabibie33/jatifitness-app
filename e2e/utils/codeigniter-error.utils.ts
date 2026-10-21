import { expect, Page } from '@playwright/test';

export async function expectNoCodeIgniterError(page: Page): Promise<void> {
  const body = page.locator('body');

  await expect(body).not.toContainText('ErrorException');
  await expect(body).not.toContainText('Undefined array key');
  await expect(body).not.toContainText('Whoops!');
}
