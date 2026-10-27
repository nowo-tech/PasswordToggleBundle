import { Application } from '@hotwired/stimulus';
import { afterAll, afterEach, beforeAll, describe, expect, it } from 'vitest';
import PasswordToggleController from '../../assets/controllers/password_toggle_controller.js';

let application;

const host = (id, inputAttrs = '') => `
  <div data-controller="nowo-password-toggle"
       data-nowo-password-toggle-visible-label-value="Show it"
       data-nowo-password-toggle-hidden-label-value="Hide it">
    <input type="password" id="${id}" value="S3cret" ${inputAttrs}>
    <span role="button" tabindex="0" data-nowo-password-toggle-target="button"
          data-action="click->nowo-password-toggle#toggle keydown->nowo-password-toggle#keydown"
          aria-label="Show it"></span>
  </div>`;

const tick = () => new Promise((resolve) => setTimeout(resolve, 0));

function parts(input) {
  const element = input.closest('[data-controller]');
  return { input, button: element.querySelector('[role="button"]') };
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

beforeAll(() => {
  application = Application.start();
  application.register('nowo-password-toggle', PasswordToggleController);
});

afterAll(() => {
  application.stop();
});

afterEach(async () => {
  document.body.innerHTML = '';
  await tick();
});

describe('password_toggle_controller', () => {
  it('toggles on click and keyboard', async () => {
    document.body.innerHTML = host('a');
    await tick();
    const p = parts(document.getElementById('a'));

    p.button.click();
    expectRevealed(p);
    p.button.click();
    expectConcealed(p);

    p.button.dispatchEvent(new KeyboardEvent('keydown', { key: 'x', bubbles: true }));
    expectConcealed(p);
    p.button.dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter', bubbles: true }));
    expectRevealed(p);
    p.button.dispatchEvent(new KeyboardEvent('keydown', { key: ' ', bubbles: true }));
    expectConcealed(p);
  });

  it('re-masks on the closest form submit before bubbling handlers run', async () => {
    document.body.innerHTML = `<form id="f">${host('a')}</form><form id="g">${host('b')}</form>`;
    await tick();
    const a = parts(document.getElementById('a'));
    const b = parts(document.getElementById('b'));
    let seen = null;
    document.getElementById('f').addEventListener('submit', (event) => {
      event.preventDefault();
      seen = a.input.type;
    });

    a.button.click();
    b.button.click();
    document.getElementById('f').dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));

    expect(seen).toBe('password');
    expectConcealed(a);
    expectRevealed(b);
  });

  it('uses the form attribute owner and re-masks on pagehide', async () => {
    document.body.innerHTML = `<form id="ext"></form>${host('c', 'form="ext"')}`;
    await tick();
    const p = parts(document.getElementById('c'));

    p.button.click();
    document.getElementById('ext').dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
    expectConcealed(p);

    p.button.click();
    window.dispatchEvent(new Event('pagehide'));
    expectConcealed(p);
  });

  it('removes its listeners on disconnect', async () => {
    document.body.innerHTML = `<form id="f">${host('d')}</form>`;
    await tick();
    const form = document.getElementById('f');
    const p = parts(document.getElementById('d'));
    p.button.click();

    const element = form.querySelector('[data-controller]');
    element.removeAttribute('data-controller');
    await tick();

    form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
    window.dispatchEvent(new Event('pagehide'));
    expect(p.input.type).toBe('text');
  });

  it('works without a form and without a button target or input', async () => {
    document.body.innerHTML = `
      <div id="nobutton" data-controller="nowo-password-toggle"><input type="password" id="e"></div>
      <div id="noinput" data-controller="nowo-password-toggle"><span role="button" data-nowo-password-toggle-target="button"
           data-action="click->nowo-password-toggle#toggle"></span></div>`;
    await tick();
    const input = document.getElementById('e');
    const controller = application.getControllerForElementAndIdentifier(document.getElementById('nobutton'), 'nowo-password-toggle');

    controller.toggle({ currentTarget: controller.element });
    expect(input.type).toBe('password');
    input.type = 'text';
    window.dispatchEvent(new Event('pagehide'));
    expect(input.type).toBe('password');

    document.querySelector('#noinput span').click();
    expect(document.querySelector('#noinput span').classList.contains('is-password-visible')).toBe(false);
    controller.toggle(undefined);
  });
});
