import { test, expect } from '@playwright/test';

test.describe('PasswordToggle demo', () => {
  test('form renders password toggle widget', async ({ page }) => {
    const response = await page.goto('/');
    expect(response?.ok()).toBeTruthy();
    await expect(page.locator('nowo-password-toggle, [data-nowo-password-toggle], .form-password-toggle').first()).toBeVisible();
  });

  test('toggle switches input to text', async ({ page }) => {
    await page.goto('/');
    const host = page.locator('nowo-password-toggle, [data-nowo-password-toggle]').first();
    await expect(host).toBeVisible();
    const password = host.locator('input[type="password"]').first();
    await password.fill('secret123');
    const button = host.locator('[data-nowo-password-toggle-target="button"], [role="button"]').first();
    await button.click();
    await expect(host.locator('input[type="text"]').first()).toBeVisible({ timeout: 5000 });
    await expect(host.locator('input[type="text"]').first()).toHaveValue('secret123');
  });
});
