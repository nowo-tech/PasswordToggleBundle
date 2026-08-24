/**
 * CSP-safe password visibility toggle for nowo-tech/password-toggle-bundle.
 *
 * Defines <nowo-password-toggle> and enhances legacy .form-password-toggle hosts.
 * Uses event delegation on the host so Live Component morphs keep working
 * without inline onclick / onkeydown handlers.
 */
(function (global) {
  'use strict';

  var TAG = 'nowo-password-toggle';
  var ATTR_INIT = 'data-nowo-password-toggle-init';
  var HOST_SELECTOR = TAG + ', [data-nowo-password-toggle], .form-password-toggle';

  /**
   * @param {string|null|undefined} value
   * @param {string} fallback
   * @returns {string}
   */
  function attrOr(value, fallback) {
    if (value === undefined || value === null || value === '') {
      return fallback;
    }
    return value;
  }

  /**
   * @param {HTMLElement} host
   * @returns {HTMLInputElement|null}
   */
  function findInput(host) {
    return host.querySelector('input');
  }

  /**
   * @param {EventTarget|null} target
   * @param {HTMLElement} host
   * @returns {HTMLElement|null}
   */
  function findToggleButton(target, host) {
    if (!(target instanceof Element)) {
      return null;
    }
    var button = target.closest('[data-nowo-password-toggle-target="button"], [role="button"]');
    if (!(button instanceof HTMLElement) || !host.contains(button)) {
      return null;
    }
    return button;
  }

  /**
   * @param {HTMLElement} host
   * @param {HTMLInputElement} input
   * @param {HTMLElement} button
   */
  function toggleVisibility(host, input, button) {
    var visibleLabel = attrOr(
      host.getAttribute('data-nowo-password-toggle-visible-label'),
      button.getAttribute('data-visible-label') || 'Show password'
    );
    var hiddenLabel = attrOr(
      host.getAttribute('data-nowo-password-toggle-hidden-label'),
      button.getAttribute('data-hidden-label') || 'Hide password'
    );

    if (input.type === 'password') {
      input.type = 'text';
      button.classList.add('is-password-visible');
      button.setAttribute('aria-label', hiddenLabel);
    } else {
      input.type = 'password';
      button.classList.remove('is-password-visible');
      button.setAttribute('aria-label', visibleLabel);
    }
  }

  /**
   * @param {HTMLElement} host
   */
  function enhanceHost(host) {
    if (host.getAttribute(ATTR_INIT) === '1') {
      return;
    }
    host.setAttribute(ATTR_INIT, '1');

    host.addEventListener('click', function (event) {
      var input = findInput(host);
      var button = findToggleButton(event.target, host);
      if (!input || !button) {
        return;
      }
      toggleVisibility(host, input, button);
    });

    host.addEventListener('keydown', function (event) {
      if (event.key !== 'Enter' && event.key !== ' ') {
        return;
      }
      var input = findInput(host);
      var button = findToggleButton(event.target, host);
      if (!input || !button) {
        return;
      }
      event.preventDefault();
      toggleVisibility(host, input, button);
    });
  }

  /**
   * @param {ParentNode} [scope]
   */
  function enhanceAll(scope) {
    var root = scope || document;
    var nodes = root.querySelectorAll(HOST_SELECTOR);
    Array.prototype.forEach.call(nodes, function (node) {
      if (node instanceof HTMLElement) {
        enhanceHost(node);
      }
    });
  }

  class NowoPasswordToggleElement extends HTMLElement {
    connectedCallback() {
      enhanceHost(this);
    }
  }

  if (typeof customElements !== 'undefined' && customElements.get(TAG) === undefined) {
    customElements.define(TAG, NowoPasswordToggleElement);
  }

  global.NowoPasswordToggle = {
    enhance: enhanceHost,
    enhanceAll: enhanceAll,
  };

  function boot() {
    enhanceAll(document);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})(typeof window !== 'undefined' ? window : this);
