import { test, expect } from '@playwright/test';

const HOST = 'nowo-password-toggle, [data-nowo-password-toggle]';
const BUTTON = '[data-nowo-password-toggle-target="button"]';

test.describe('PasswordToggle demo', () => {
  test('form renders password toggle widget', async ({ page }) => {
    const response = await page.goto('/');
    expect(response?.ok()).toBeTruthy();
    const host = page.locator(HOST).first();
    await expect(host).toBeVisible();
    // 2.4.1: structural container class rendered exactly once.
    const classes = ((await host.getAttribute('class')) ?? '').split(/\s+/);
    expect(classes.filter((c) => c === 'form-password-toggle')).toHaveLength(1);
  });

  test('toggle switches input to text with exactly one eye icon visible', async ({ page }) => {
    await page.goto('/');
    const host = page.locator(HOST).first();
    await expect(host).toBeVisible();
    const button = host.locator(BUTTON).first();
    const icons = button.locator('svg, i');

    // Hidden state: only the eye-off icon is shown.
    await expect(icons.locator('visible=true')).toHaveCount(1);
    await expect(button.locator('.icon-hidden')).toBeVisible();

    const password = host.locator('input[type="password"]').first();
    await password.fill('secret123');
    await button.click();
    await expect(host.locator('input[type="text"]').first()).toBeVisible({ timeout: 5000 });
    await expect(host.locator('input[type="text"]').first()).toHaveValue('secret123');

    // Revealed state: only the eye icon is shown.
    await expect(icons.locator('visible=true')).toHaveCount(1);
    await expect(button.locator('.icon-visible')).toBeVisible();
  });

  test('always_empty: password is not echoed back after a failed submit', async ({ page }) => {
    await page.goto('/');
    const host = page.locator(HOST).first();
    await host.locator('input[type="password"]').first().fill('secret123');
    // Username left empty -> server-side validation fails and the form is re-rendered.
    await page.locator('form').first().evaluate((form: HTMLFormElement) => form.submit());
    await page.waitForLoadState('load');
    await expect(page.locator(HOST).first().locator('input').first()).toHaveValue('');
  });

  test('2.6.1: a revealed password is masked again when its form is submitted', async ({ page }) => {
    await page.goto('/');
    const host = page.locator(HOST).first();
    const button = host.locator(BUTTON).first();
    const input = host.locator('input').first();
    await input.fill('secret123');
    await button.click();
    await expect(input).toHaveAttribute('type', 'text');

    const typeAtSubmit = await page.locator('form').first().evaluate((form: HTMLFormElement) => {
      let seen = '';
      form.addEventListener('submit', (event) => {
        event.preventDefault();
        seen = (form.querySelector('nowo-password-toggle input, [data-nowo-password-toggle] input') as HTMLInputElement).type;
      }, { once: true });
      form.requestSubmit();
      return seen;
    });

    expect(typeAtSubmit).toBe('password');
    await expect(input).toHaveAttribute('type', 'password');
    await expect(input).toHaveValue('secret123');
    await expect(button).not.toHaveClass(/is-password-visible/);
  });
});
