# Security Policy

## Table of contents

- [Supported Versions](#supported-versions)
- [Reporting a Vulnerability](#reporting-a-vulnerability)
  - [How to Report](#how-to-report)
  - [Response Timeline](#response-timeline)
  - [Disclosure Policy](#disclosure-policy)
- [Security fixes and recommended defaults](#security-fixes-and-recommended-defaults)
  - [2.3.2 — `always_empty` is honoured](#232--always_empty-is-honoured)
  - [Recommended defaults](#recommended-defaults)
- [Preferred Languages](#preferred-languages)
- [Contact](#contact)
- [Release security checklist (12.4.1)](#release-security-checklist-1241)

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

### Recommended defaults

- Keep **`always_empty: true`** (bundle config and per field). Only disable it if you have a concrete UX need and the page is served over HTTPS with `Cache-Control: no-store`.
- Keep **`trim: false`**: whitespace is significant in passwords.
- Set the right `autocomplete` attribute per field (`current-password` on login, `new-password` on registration / change forms) via `attr`.
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
| **SECURITY.md** | This document is current and linked from the README where applicable. |
| **`.gitignore` and `.env`** | `.env` and local env files are ignored; no committed secrets. |
| **No secrets in repo** | No API keys, passwords, or tokens in tracked files. |
| **Recipe / Flex** | Default recipe or installer templates do not ship production secrets. |
| **Input / output** | Inputs validated; outputs escaped in Twig/templates where user-controlled. |
| **Dependencies** | `composer audit` run; issues triaged. |
| **Logging** | Logs do not print secrets, tokens, or session identifiers unnecessarily. |
| **Cryptography** | If used: keys from secure config; never hardcoded. |
| **Permissions / exposure** | Routes and admin features documented; roles configured for production. |
| **Limits / DoS** | Timeouts, size limits, rate limits where applicable. |

Record confirmation in the release PR or tag notes.

