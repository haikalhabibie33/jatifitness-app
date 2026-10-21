import { LocatorType } from '#types/locator.js';
import { Page } from '@playwright/test';

export default class ElementDebugUtils {
  constructor(private readonly page: Page) {}

  public async inspectClickable(locator: LocatorType): Promise<void> {
    const el = this.page.getByTestId(locator.value);

    console.log('visible:', await el.isVisible());
    console.log('enabled:', await el.isEnabled());
    console.log('boundingBox:', await el.boundingBox());
    console.log('class:', await el.getAttribute('class'));

    const info = await this.page.evaluate((testId) => {
      const btn = document.querySelector(`[data-testid="${testId}"]`);
      if (!btn) return null;

      const rect = btn.getBoundingClientRect();
      const x = rect.left + rect.width / 2;
      const y = rect.top + rect.height / 2;

      const topEl = document.elementFromPoint(x, y);

      return {
        sameElement: btn === topEl,
        topTag: topEl?.tagName,
        topClass: topEl?.className,
        topHtml: topEl?.outerHTML?.slice(0, 300),
      };
    }, locator.value);

    console.log('top element info:', info);
  }

  public async debugModalState(selector: string): Promise<void> {
    const modal = this.page.locator(selector);

    console.log('modal visible:', await modal.isVisible());
    console.log('modal class:', await modal.getAttribute('class'));
    console.log('modal style:', await modal.getAttribute('style'));
  }

  public async screenshot(name: string): Promise<void> {
    await this.page.screenshot({ path: `${name}.png`, fullPage: true });
  }
}
