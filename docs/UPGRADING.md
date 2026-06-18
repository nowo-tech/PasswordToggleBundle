# Upgrading

This document describes how to upgrade between major versions of Password Toggle Bundle.

## 1.x

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

- **Demo paths**: If you reference demo directories in scripts or CI, update paths from `demo/demo-symfony6` etc. to `demo/symfony6`, `demo/symfony7`, `demo/symfony8`, `demo/symfony8-php85`.
- **PHPStan**: If you extended or copied `phpstan.neon.dist`, remove `memoryLimit` and `checkMissingIterableValueType` (not supported in PHPStan 2.x). No impact on runtime or bundle API.

### 1.2.0

- Global configuration file `config/packages/nowo_password_toggle.yaml` is supported; demos and new Flex installs use it. No breaking changes if you were using defaults.

### 1.0.0

First stable release.

- **Requirements:** PHP >= 8.1, Symfony ^6.0 || ^7.0 || ^8.0.
- **Optional:** symfony/ux-icons ^2.0 || ^3.0 for icon support.
