import { test, expect } from '@playwright/test';
import { mkdirSync } from 'node:fs';
import { resolve } from 'node:path';

/**
 * REQ-DEMO-013 — crop to the demo use-case panel (header + form with toggle),
 * not a naked <nowo-password-toggle> and not full-page chrome.
 */
const outDir = process.env.SCREENSHOT_DIR
  ? resolve(process.env.SCREENSHOT_DIR)
  : resolve(__dirname, '../../../../docs/images/demo');

function useCasePanel(page: import('@playwright/test').Page) {
  return page.locator('.demo-container').first();
}

function toggleHost(page: import('@playwright/test').Page) {
  return page.locator('nowo-password-toggle, [data-nowo-password-toggle]').first();
}

test.beforeAll(() => {
  mkdirSync(outDir, { recursive: true });
});

test.describe('PasswordToggle screenshots (use-case context)', () => {
  test('overview — masked password in demo form', async ({ page }) => {
    await page.goto('/');
    const panel = useCasePanel(page);
    await expect(panel).toBeVisible();
    const host = toggleHost(page);
    await host.locator('input').first().fill('DemoPass!1');
    await expect(host.locator('input[type="password"]').first()).toBeVisible();
    await panel.screenshot({ path: resolve(outDir, 'overview.png') });
  });

  test('interaction — password revealed in demo form', async ({ page }) => {
    await page.goto('/');
    const panel = useCasePanel(page);
    await expect(panel).toBeVisible();
    const host = toggleHost(page);
    await host.locator('input').first().fill('DemoPass!1');
    await host.locator('[data-nowo-password-toggle-target="button"], [role="button"]').first().click();
    await expect(host.locator('input[type="text"]').first()).toBeVisible({ timeout: 5000 });
    await panel.screenshot({ path: resolve(outDir, 'interaction.png') });
  });
});
