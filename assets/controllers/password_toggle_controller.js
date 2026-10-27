import { Controller } from '@hotwired/stimulus';

/**
 * Optional Stimulus controller for nowo-tech/password-toggle-bundle (CSP-safe: no inline handlers).
 *
 * Enable with `nowo_password_toggle.javascript: stimulus`; the widget then renders, on the host:
 *   data-controller="nowo-password-toggle"
 *   data-nowo-password-toggle-visible-label-value="Show password"
 *   data-nowo-password-toggle-hidden-label-value="Hide password"
 * and on the toggle button:
 *   data-nowo-password-toggle-target="button"
 *   data-action="click->nowo-password-toggle#toggle keydown->nowo-password-toggle#keydown"
 *
 * Register it under the identifier configured in `stimulus_controller` (default "nowo-password-toggle"):
 *   import PasswordToggleController from '<vendor>/nowo-tech/password-toggle-bundle/assets/controllers/password_toggle_controller.js';
 *   app.register('nowo-password-toggle', PasswordToggleController);
 *
 * Icon visibility is driven by the `is-password-visible` class + toggle_password.css (no style mutation,
 * so a nonce-based style-src does not leave both icons visible).
 */
export default class extends Controller {
  static targets = ['button'];

  static values = {
    visibleLabel: { type: String, default: 'Show password' },
    hiddenLabel: { type: String, default: 'Hide password' },
  };

  connect() {
    this.concealHandler = () => this.conceal();
    const input = this.element.querySelector('input');
    this.form = (input instanceof HTMLInputElement && input.form) || this.element.closest('form');
    if (this.form) {
      this.form.addEventListener('submit', this.concealHandler, true);
    }
    window.addEventListener('pagehide', this.concealHandler);
  }

  disconnect() {
    if (this.form) {
      this.form.removeEventListener('submit', this.concealHandler, true);
    }
    window.removeEventListener('pagehide', this.concealHandler);
    this.form = null;
  }

  toggle(event) {
    const input = this.element.querySelector('input');
    const button = this.resolveButton(event);
    if (!(input instanceof HTMLInputElement) || !button) {
      return;
    }

    if (input.type === 'password') {
      input.type = 'text';
      button.classList.add('is-password-visible');
      button.setAttribute('aria-label', this.hiddenLabelValue);
      return;
    }

    this.conceal(button);
  }

  /**
   * Masks a revealed password again (type="password", icon class removed, "show" label restored).
   * Runs on toggle, on the owning form's submit (capture phase) and on pagehide, so a revealed value
   * is not left on screen, in the bfcache snapshot or offered to the browser as a plain text field.
   *
   * @param {HTMLElement|null} [toggleButton] button that triggered the toggle (defaults to the button target)
   */
  conceal(toggleButton = null) {
    const input = this.element.querySelector('input');
    if (input instanceof HTMLInputElement && input.type === 'text') {
      input.type = 'password';
    }
    const button = toggleButton || (this.hasButtonTarget ? this.buttonTarget : null);
    if (button) {
      button.classList.remove('is-password-visible');
      button.setAttribute('aria-label', this.visibleLabelValue);
    }
  }

  keydown(event) {
    if (event.key !== 'Enter' && event.key !== ' ') {
      return;
    }
    event.preventDefault();
    this.toggle(event);
  }

  resolveButton(event) {
    if (event && event.currentTarget instanceof HTMLElement && event.currentTarget !== this.element) {
      return event.currentTarget;
    }
    return this.hasButtonTarget ? this.buttonTarget : null;
  }
}
