# Upgrading

This document describes how to upgrade between major versions of Password Toggle Bundle.

## 2.1.1 (CSP-safe icon visibility)

- **Load the bundle CSS** (`asset('css/toggle_password.css', 'nowo_password_toggle')` or import the SCSS). Icon show/hide now depends on `.icon-visible` / `.is-password-visible` rules in that stylesheet.
- **Custom form theme overrides:** if you copied `toggle_password_widget.html.twig`, stop using inline `style.display` / `style: 'display: none'` / Bootstrap `d-none`. Toggle `is-password-visible` on the button (see the bundle widget) and keep both icons in the markup without inline hide styles.
- **Hosts with strict `script-src` (no `'unsafe-inline'`):** the default widget still uses inline `onclick` for Live Component compatibility; override the form theme with a Stimulus (or other non-inline) controller if needed — same as before.

## 2.0.5

- **No Twig / `PasswordType` config changes** for normal use.
- **Optional CSS via named assets:** install `symfony/asset` if you use `asset('css/toggle_password.css', 'nowo_password_toggle')`. Without it the named package is not registered (no hard failure).
- **Do not extend** `NowoPasswordToggleBundle`, `NowoPasswordToggleExtension`, or `Configuration` — they are now `final`. Prefer composition or decorating services instead.
- **Tests that called** `IconSupportWarningSubscriber::resetWarningState()`: use instance `$subscriber->reset()` (implements `ResetInterface`; autoconfigured for `kernel.reset` under FrankenPHP worker).
- **Demos:** prefer `FRANKENPHP_MODE=classic|worker` (see [`DEMO-FRANKENPHP.md`](DEMO-FRANKENPHP.md)); `symfony8-php85` runs on FrankenPHP PHP 8.5; demos require `symfony/asset`.
- **Maintainers:** `make release-check-demos` / `demo release-check` now fail the build if demos fail; use `make demo-smoke` for a quick HTTP check.

## 2.0.4

- **No application upgrade steps.** Repository and maintainer tooling only (REQ-GIT-001 git hygiene, Code of Conduct, CI docs, dev dependency bumps).
- **Maintainers / contributors:** run `make setup-hooks` once; CI enforces no Cursor co-author trailers — see [`GITHUB_CI.md`](GITHUB_CI.md).

## 2.0.3

- **Optional (AssetMapper / named assets):** prefer `asset('css/toggle_password.css', 'nowo_password_toggle')` in Twig instead of `asset('bundles/nowopasswordtoggle/css/toggle_password.css')`. Run `php bin/console assets:install` once so the CSS is published under `public/bundles/nowopasswordtoggle/`. The legacy path still works for classic asset setups.
- **No PHP or YAML config changes.**

## 2.0.2

- **No application upgrade steps.** Repository and maintainer tooling only (Spec Kit, baseline specs, dev dependency bumps, demo Docker `intl` extension).
- **Maintainers:** see [`SPEC-KIT.md`](SPEC-KIT.md) and [`SPEC-DRIVEN-DEVELOPMENT.md`](SPEC-DRIVEN-DEVELOPMENT.md).

## 2.0.1

- **Demos / CI:** if you referenced `demo/symfony8/` in scripts or docs, use `symfony7`, `symfony8`, or `symfony8-php85`.

## 2.0.0

Breaking release for projects still on PHP 8.1 or Symfony 6.x.

- **PHP:** upgrade to **>= 8.2** before updating.
- **Symfony:** upgrade to **^7.0** or **^8.0** before updating.
- **Composer:** `composer require nowo-tech/password-toggle-bundle:^2.0`.
- **Stay on 1.x:** if you cannot upgrade yet, keep `nowo-tech/password-toggle-bundle:^1.2` (last line with Symfony 6 / PHP 8.1 support is **1.2.10**).
- **Demos / CI:** `symfony6` was removed from `demo/Makefile` `DEMOS`; use `symfony7`, `symfony8`, or `symfony8-php85` (the `demo/symfony8/` tree was removed in **2.0.1**).
- **No API changes** to `PasswordType` or `nowo_password_toggle` configuration; only platform requirements changed.

## 1.x

### 1.2.10

- **No application upgrade steps.** CI now enforces **≥95%** coverage instead of 100%. No PHP, config, or template changes.

### 1.2.9

- **No upgrade steps required.** Patch release: internal `IconSupportChecker` cleanup (Twig runtime class detection only) and restored 100% test coverage for CI. Behaviour unchanged when `symfony/ux-icons` is installed.

### 1.2.8

- **Custom form theme overrides:** if you copied `toggle_password_widget.html.twig` from an older bundle version, align icon show/hide with **1.2.8** (inline `style.display` instead of `d-none`) or re-copy from `@NowoPasswordToggleBundle/Form/toggle_password_widget.html.twig`. No PHP or YAML config changes.

### 1.2.7

- **Icons still missing after 1.2.6:** upgrade to **1.2.7** if `[icons missing]` appears in dev while `icons_available` should be true (packages installed, cache cleared). The default widget incorrectly used `ux_icon is defined`, which Twig never satisfies for functions. No config changes; run `php bin/console cache:clear` after updating.

### 1.2.6

- **False `[icons missing]` in dev:** upgrade from **1.2.5** if the toggle works but dev still shows `[icons missing]` while `symfony/ux-icons` and `symfony/http-client` are installed. This release fixes `IconSupportChecker` for UX Icons 3.x (`UXIconRuntime`). Then run `php bin/console cache:clear` (icons are usually already locked via `ux:icons:lock`).

### 1.2.5

- **UX Icons 3.x:** replace `php bin/console ux:icons:lock tabler:eye tabler:eye-off` with `php bin/console ux:icons:lock` in scripts and CI. The command scans Twig templates; optional `--force` overwrites local SVGs. Demos and Flex recipe post-install are updated in this release.

### 1.2.4

- **No breaking API changes** for `PasswordType` or `nowo_password_toggle` config.
- **Recommended:** ensure `symfony/ux-icons` and `symfony/http-client` are installed if you use the default widget. Symfony Flex recipe **1.2.3+** adds both automatically on new installs.
- **Without those packages:** compile/cache warmup still works; you get a log warning once per process and icons are skipped (see [INSTALLATION.md](INSTALLATION.md#missing-icon-packages-runtime-behaviour)).
- **Manual upgrade:** `composer require symfony/ux-icons symfony/http-client` then `php bin/console ux:icons:lock`.

### 1.2.3

- **Twig overrides**: The bundle now registers its Twig paths so that **application** overrides under `templates/bundles/NowoPasswordToggleBundle/` take precedence over the bundle templates. If you relied on the opposite order (unlikely), adjust your templates or copy the bundle widget into your project.
- **Demos / Docker**: If you copied demo `composer.json`, use the `dev-main as 1.2.99` constraint (and `minimum-stability` / `prefer-stable`) when using a **path** repository for the bundle, or Composer will not resolve `^1.2.0` against the mounted source. Applications consuming the package from Packagist are unchanged.

### 1.2.2

- No breaking changes. Demos now document FrankenPHP-based setup; see [Demo with FrankenPHP](DEMO-FRANKENPHP.md) if you run or adapt the bundled demos.

### 1.2.1

- **Demo paths**: If you reference demo directories in scripts or CI, update paths from `demo/demo-symfony6` etc. to `demo/symfony8`, `demo/symfony8-php85`.
- **PHPStan**: If you extended or copied `phpstan.neon.dist`, remove `memoryLimit` and `checkMissingIterableValueType` (not supported in PHPStan 2.x). No impact on runtime or bundle API.

### 1.2.0

- Global configuration file `config/packages/nowo_password_toggle.yaml` is supported; demos and new Flex installs use it. No breaking changes if you were using defaults.

### 1.0.0

First stable release.

- **Requirements:** PHP >= 8.1, Symfony ^6.0 || ^7.0 || ^8.0.
- **Optional:** symfony/ux-icons ^2.0 || ^3.0 for icon support.

## Unreleased

## To 2.1.0

From **2.0.5** — Adds required Twig Extra (REQ-TWIG-004) and Twig-CS-Fixer. Register TwigExtraBundle if Flex did not.

```bash
composer update nowo-tech/password-toggle-bundle
php bin/console cache:clear
```

### Twig Extra Bundle (REQ-TWIG-004)

Hosts that render this bundle's Twig templates must install:

```bash
composer require twig/extra-bundle twig/string-extra
```

and enable `Twig\Extra\TwigExtraBundle\TwigExtraBundle`. Flex recipes usually register it automatically.

### Twig-CS-Fixer (maintainers)

Package maintainers: `composer twig:lint` / `composer twig:fix` use `.twig-cs-fixer.php` over `src/` (and `templates/` when present).

