# Security Policy

## Table of contents

- [Attack surface](#attack-surface)
- [Threat model](#threat-model)
- [Logging](#logging)
- [Dependencies](#dependencies)
- [Supported Versions](#supported-versions)
- [Reporting a Vulnerability](#reporting-a-vulnerability)
  - [How to Report](#how-to-report)
  - [Response Timeline](#response-timeline)
  - [Disclosure Policy](#disclosure-policy)
- [Security fixes and recommended defaults](#security-fixes-and-recommended-defaults)
  - [2.3.2 — `always_empty` is honoured](#232--always_empty-is-honoured)
  - [2.6.1 — revealed password re-masked; `stimulus_controller` validated per field](#261--revealed-password-re-masked-stimulus_controller-validated-per-field)
  - [Recommended defaults](#recommended-defaults)
- [Preferred Languages](#preferred-languages)
- [Contact](#contact)
- [Release security checklist (12.4.1)](#release-security-checklist-1241)
- [AI security audit](#ai-security-audit)

## Attack surface

| Input | Where | Notes |
| --- | --- | --- |
| Submitted password | `PasswordType` (extends Symfony `TextType`; `buildView()` blanks the value the same way Symfony's core `PasswordType` does) | Plain text in the request; never rendered back with `always_empty: true` |
| Field options / bundle config | `nowo_password_toggle` YAML, form options | Developer-controlled (icons, labels, CSP nonce attribute, JS mode) |
| CSP nonce | Request attribute (`csp_nonce_attribute`, default `csp_nonce`) | Read and stamped on the widget's external `<script src>` only |
| Widget JS | `public/js/nowo-password-toggle.js` (or the Stimulus `password_toggle_controller.js`) | Toggles the input `type` only and re-masks it on form submit / `pagehide`; no network access |

The bundle exposes **no HTTP routes**, performs no outbound HTTP, stores nothing and runs no subprocesses.

## Threat model

| Threat | Mitigation |
| --- | --- |
| Password echoed into HTML / caches | `always_empty: true` by default (fixed in 2.3.2, see below) |
| XSS through labels, classes or icons | Twig auto-escaping (regression-tested with `"` and `<` in labels and class options); icon names are passed to `ux_icon()`, not rendered as raw HTML; `stimulus_controller` must match the Stimulus identifier pattern (bundle config and per field, 2.6.1) because it is used inside attribute names |
| Revealed password left visible | Since 2.6.1 the widget JS and the Stimulus controller set the input back to `type="password"` on the owning form's `submit` (capture phase) and on `pagehide`, so a revealed value is not left on screen, in the back/forward cache snapshot or offered to the browser as a plain text field |
| Script blocked by CSP / CSP bypass | The widget only emits an external `<script src>` (never inline code), carrying the nonce read from the request attribute; `javascript: stimulus` / `none` emit no script at all |
| Supply chain (shipped JS) | Small committed source, no third-party JS runtime dependency |

## Logging

`IconSupportWarningSubscriber` logs a single `warning` per process when the configured icons cannot be rendered (missing `symfony/ux-icons` / icon set). **Never logged:** passwords, field values, request data or user identifiers.

## Dependencies

Runtime requirements: Symfony Form / FrameworkBundle / TwigBundle, `twig/extra-bundle`, `psr/log`. `composer audit` runs in CI; Dependabot proposes updates; security fixes ship as patch releases of the supported major (see below).

## Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| 2.x     | :white_check_mark: |
| 1.x     | :white_check_mark: (security fixes only) |

## Reporting a Vulnerability

We take the security of Password Toggle Bundle seriously. If you believe you have found a security vulnerability, please report it to us as described below.

### How to Report

**Please do not report security vulnerabilities through public GitHub issues.**

Instead, please send an email to: **hectorfranco@nowo.tech**

Include the following information in your report:

- Type of issue (e.g., buffer overflow, SQL injection, cross-site scripting, etc.)
- Full paths of source file(s) related to the issue
- The location of the affected source code (tag/branch/commit or direct URL)
- Any special configuration required to reproduce the issue
- Step-by-step instructions to reproduce the issue
- Proof-of-concept or exploit code (if possible)
- Impact of the issue, including how an attacker might exploit it

### Response Timeline

- **Initial Response**: Within 48 hours
- **Status Update**: Within 7 days
- **Resolution**: Varies depending on complexity

### Disclosure Policy

- We will confirm receipt of your vulnerability report
- We will work with you to understand and validate the issue
- We will develop and release a fix as quickly as possible
- We will publicly acknowledge your responsible disclosure (if desired)

## Security fixes and recommended defaults

### 2.3.2 — `always_empty` is honoured

Before **2.3.2**, `Nowo\PasswordToggleBundle\Form\Type\PasswordType` ignored `always_empty`: after a failed submit (e.g. a validation error elsewhere in the form) the submitted **plaintext password was rendered back** into the input `value` attribute, where it could end up in the HTML source, browser caches, proxies or page snapshots.

Since 2.3.2 the type behaves like Symfony's core `PasswordType`: with `always_empty: true` (the default) the value is always rendered empty; with `always_empty: false` the submitted value is kept after a submit, and initial model data is never rendered. **Upgrade to 2.3.2 or later** (all 2.x releases from 2.3.2 include the fix).

### 2.6.1 — revealed password re-masked; `stimulus_controller` validated per field

- **L1:** a password revealed with the toggle stayed `type="text"` through form submission and page unload. Since 2.6.1 both the web component script and the Stimulus controller restore `type="password"`, remove `is-password-visible` and restore the "show" `aria-label` on the owning form's `submit` (capture phase) and on `pagehide`.
- **L2:** the per-field `stimulus_controller` option was only type-checked, while the bundle-level option was validated against the Stimulus identifier pattern. Since 2.6.1 the form option uses the same pattern (`PasswordType::STIMULUS_IDENTIFIER_PATTERN`), so an invalid identifier fails at form build time.

### Recommended defaults

- Keep **`always_empty: true`** (bundle config and per field). Only disable it if you have a concrete UX need and the page is served over HTTPS with `Cache-Control: no-store`.
- Keep **`trim: false`**: whitespace is significant in passwords.
- Set the right `autocomplete` attribute per field (`current-password` on login, `new-password` on registration / change forms) via `attr`.
- Lock the UX icons in production (`php bin/console ux:icons:lock`): the Flex recipe installs `symfony/ux-icons` + `symfony/http-client`, and unlocked icons are fetched on demand from the Iconify API.
- Do not HTTP-cache pages that carry a per-request CSP nonce (a cached nonce defeats the policy).
- With a nonce-based Content Security Policy, store the nonce in the request attribute named by `csp_nonce_attribute` (default `csp_nonce`) so the widget's `<script>` carries it, or use `javascript: stimulus` / `none` and load the JS yourself. See [Usage → Content Security Policy](USAGE.md#content-security-policy-csp).

## Preferred Languages

We prefer all communications to be in English or Spanish.

## Contact

- **Maintainer**: [Héctor Franco Aceituno](https://github.com/HecFranco)
- **Organization**: [nowo-tech](https://github.com/nowo-tech)

## Release security checklist (12.4.1)

Before tagging a release, confirm:

| Item | Notes |
|------|--------|
| **SECURITY.md** | This document is current and linked from the README. |
| **`.gitignore` and `.env`** | `.env` and local env files are ignored; the demo ships `.env.example` only; no committed secrets. |
| **No secrets in repo** | No API keys, passwords or tokens in tracked files (fixtures use obvious placeholders). |
| **Recipe / Flex** | The Flex recipe (`.symfony/recipes/nowo-tech/password-toggle-bundle/`) only registers the bundle, requires `symfony/ux-icons` + `symfony/http-client`, writes the default `nowo_password_toggle.yaml` and copies the tabler SVGs; it ships no secrets. `ux_icons` fetches unlocked icons from the Iconify API, so the docs keep advising `ux:icons:lock` in production. |
| **Input / output** | `always_empty: true` stays the default (password never rendered back); labels, classes and attributes go through Twig auto-escaping (`TogglePasswordWidgetRenderTest`); `stimulus_controller` is validated against the identifier pattern; icon names are passed to `ux_icon()`, never rendered as raw HTML; the JS re-masks revealed passwords on submit / `pagehide`. |
| **Dependencies** | `composer audit` run; issues triaged ([Dependencies](#dependencies)). |
| **No-secret logs** | [Logging](#logging) still holds: only the icon-support warning; never passwords, field values or request data (`grep -rn 'logger' src/`). |
| **Cryptography** | Not applicable: the bundle performs no cryptography and stores nothing. |
| **Permissions / exposure** | Still no HTTP routes, controllers, outbound HTTP, storage or subprocesses ([Attack surface](#attack-surface)); a release that adds any of these needs a new AI security audit. |
| **CSP** | The external widget `<script src>` still carries the nonce from `csp_nonce_attribute` (no inline code); `javascript: stimulus` / `none` still emit no script. |
| **Limits / DoS** | No server-side processing beyond form rendering/submission; the widget JS only toggles the input `type` and makes no network calls. |
| **AI security audit (REQ-SEC-004)** | Package **passes** the latest AI security audit: grade **Pass (good)** / risk **Low** (2026-10-27), recorded in the Nowo monorepo `BUNDLES_SECURITY_ANALYSIS.md` Appendix AJ (see [AI security audit](#ai-security-audit)). Do not release on a failing or missing grade; re-run the audit when the release adds attack surface. |

Record confirmation in the release PR or tag notes.

Recommended commands:

```bash
composer audit
make release-check
```

## AI security audit

| Field | Value |
| ----- | ----- |
| Latest recorded audit | 2026-10-27 (re-audit of `main` `8e9e6d0`, v2.6.0 + PR #32) |
| Grade | **Pass (good)** |
| Risk | Low |
| Method | Claude Code static security review (`src/`, widget Twig, JavaScript, Stimulus controller, Flex recipes, docs) |
| Findings | **L1** revealed password not re-masked on submit / page unload — **Fixed in 2.6.1**. **L2** per-field `stimulus_controller` not validated against the identifier pattern — **Fixed in 2.6.1**. Info items: I1 `docs/SECURITY.md` accuracy (fixed in 2.6.1), I4 escaping regression test for labels and class options (added in 2.6.1); the remaining Info items need no code change (see Appendix AJ). |
| Residuals | The host sets the right `autocomplete` and keeps `always_empty: true`; the host provides the CSP nonce and does not HTTP-cache pages carrying it; lock the UX icons in production (`ux:icons:lock`). |
| Record | `BUNDLES_SECURITY_ANALYSIS.md` (Nowo bundles monorepo), Appendix AJ |
