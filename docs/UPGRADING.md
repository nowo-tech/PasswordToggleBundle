# Upgrading

This document describes how to upgrade between major versions of Password Toggle Bundle.

## 1.x

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
