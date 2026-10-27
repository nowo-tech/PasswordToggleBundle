import { afterEach, beforeAll, describe, expect, it } from 'vitest';

const markup = (id = 'pw', extraHost = '') => `
  <nowo-password-toggle class="input-group form-password-toggle" data-nowo-password-toggle
      data-nowo-password-toggle-visible-label="Show it" data-nowo-password-toggle-hidden-label="Hide it" ${extraHost}>
    <input type="password" id="${id}" name="${id}" value="S3cret">
    <span role="button" tabindex="0" data-nowo-password-toggle-target="button" aria-label="Show it"></span>
  </nowo-password-toggle>`;

/** @returns {{host: HTMLElement, input: HTMLInputElement, button: HTMLElement}} */
function parts(root = document) {
  const host = root.querySelector('nowo-password-toggle');
  return { host, input: host.querySelector('input'), button: host.querySelector('[role="button"]') };
}

function expectConcealed({ input, button }) {
  expect(input.type).toBe('password');
  expect(button.classList.contains('is-password-visible')).toBe(false);
  expect(button.getAttribute('aria-label')).toBe('Show it');
}

function expectRevealed({ input, button }) {
  expect(input.type).toBe('text');
  expect(button.classList.contains('is-password-visible')).toBe(true);
  expect(button.getAttribute('aria-label')).toBe('Hide it');
}

beforeAll(async () => {
  await import('../../src/Resources/public/js/nowo-password-toggle.js');
});

afterEach(() => {
  document.body.innerHTML = '';
});

describe('nowo-password-toggle.js', () => {
  it('toggles on click and restores the show label', () => {
    document.body.innerHTML = markup();
    const p = parts();

    p.button.click();
    expectRevealed(p);
    p.button.click();
    expectConcealed(p);
  });

  it('toggles on Enter / Space and ignores other keys', () => {
    document.body.innerHTML = markup();
    const p = parts();

    p.button.dispatchEvent(new KeyboardEvent('keydown', { key: 'a', bubbles: true }));
    expectConcealed(p);
    p.button.dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter', bubbles: true }));
    expectRevealed(p);
    p.button.dispatchEvent(new KeyboardEvent('keydown', { key: ' ', bubbles: true }));
    expectConcealed(p);
  });

  it('ignores clicks outside the toggle button and hosts without input', () => {
    document.body.innerHTML = markup();
    const p = parts();
    p.input.click();
    expectConcealed(p);

    document.body.innerHTML = '<div data-nowo-password-toggle><span role="button">x</span></div>';
    const host = document.querySelector('[data-nowo-password-toggle]');
    window.NowoPasswordToggle.enhance(host);
    host.querySelector('span').click();
    host.querySelector('span').dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter', bubbles: true }));
    window.NowoPasswordToggle.conceal(host);
    expect(host.querySelector('span').classList.contains('is-password-visible')).toBe(false);
  });

  it('falls back to button / default labels and enhances legacy hosts once', () => {
    document.body.innerHTML = `
      <div class="form-password-toggle"><input type="password"><span role="button" data-visible-label="V" data-hidden-label="H"></span></div>
      <div class="form-password-toggle"><input type="password"><span role="button"></span></div>`;
    window.NowoPasswordToggle.enhanceAll();
    window.NowoPasswordToggle.enhanceAll(document);
    const [a, b] = document.querySelectorAll('.form-password-toggle');

    a.querySelector('span').click();
    expect(a.querySelector('span').getAttribute('aria-label')).toBe('H');
    a.querySelector('span').click();
    expect(a.querySelector('input').type).toBe('password');
    expect(a.querySelector('span').getAttribute('aria-label')).toBe('V');

    b.querySelector('span').click();
    expect(b.querySelector('span').getAttribute('aria-label')).toBe('Hide password');
    window.NowoPasswordToggle.concealAll();
    expect(b.querySelector('span').getAttribute('aria-label')).toBe('Show password');
  });

  it('re-masks a revealed password when its form is submitted (capture phase)', () => {
    document.body.innerHTML = `<form id="f">${markup('a')}</form><form id="g">${markup('b')}</form>`;
    const hosts = document.querySelectorAll('nowo-password-toggle');
    const a = parts(hosts[0].parentElement);
    const b = parts(hosts[1].parentElement);
    let typeSeenByHostHandler = null;
    document.getElementById('f').addEventListener('submit', (event) => {
      event.preventDefault();
      typeSeenByHostHandler = a.input.type;
    });

    a.button.click();
    b.button.click();
    document.getElementById('f').dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));

    expectConcealed(a);
    expect(typeSeenByHostHandler).toBe('password');
    expectRevealed(b);
    expect(b.input.value).toBe('S3cret');
  });

  it('re-masks inputs associated through the form attribute', () => {
    document.body.innerHTML = `<form id="ext"></form>${markup('c').replace('<input ', '<input form="ext" ')}`;
    const p = parts();
    p.button.click();
    document.getElementById('ext').dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
    expectConcealed(p);
  });

  it('re-masks every revealed password on pagehide and leaves other input types alone', () => {
    document.body.innerHTML = markup() + '<div data-nowo-password-toggle><input type="email"><span role="button"></span></div>';
    const p = parts();
    const email = document.querySelector('input[type="email"]');
    p.button.click();

    window.dispatchEvent(new Event('pagehide'));

    expectConcealed(p);
    expect(email.type).toBe('email');
  });

  it('ignores submit events whose target is not a form', () => {
    document.body.innerHTML = markup();
    const p = parts();
    p.button.click();
    document.body.dispatchEvent(new Event('submit', { bubbles: true }));
    expectRevealed(p);
  });

  it('ignores non-element event targets and non-HTML hosts', () => {
    document.body.innerHTML = markup() + '<svg class="form-password-toggle"></svg>';
    const p = parts();
    const text = document.createTextNode('x');
    p.host.appendChild(text);

    text.dispatchEvent(new MouseEvent('click', { bubbles: true }));
    expectConcealed(p);

    window.NowoPasswordToggle.enhanceAll();
    p.button.click();
    window.dispatchEvent(new Event('pagehide'));
    expectConcealed(p);
  });
});
