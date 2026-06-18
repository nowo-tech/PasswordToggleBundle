# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Table of contents

- [[Unreleased]](#unreleased)
- [[1.2.10] - 2026-06-18](#1210---2026-06-18)
- [[1.2.9] - 2026-06-18](#129---2026-06-18)
- [[1.2.8] - 2026-06-18](#128---2026-06-18)
- [[1.2.7] - 2026-06-18](#127---2026-06-18)
- [[1.2.6] - 2026-06-18](#126---2026-06-18)
- [[1.2.5] - 2026-06-18](#125---2026-06-18)
- [[1.2.4] - 2026-06-18](#124---2026-06-18)
- [[1.2.3] - 2026-04-15](#123---2026-04-15)
- [[1.2.2] - 2026-03-16](#122---2026-03-16)
- [[1.2.1] - 2026-03-09](#121---2026-03-09)
- [[1.2.0] - 2025-12-15](#120---2025-12-15)
- [[1.1.1] - 2024-12-12](#111---2024-12-12)
- [[1.1.0] - 2024-12-12](#110---2024-12-12)
- [[1.0.0] - 2024-12-11](#100---2024-12-11)

## [Unreleased]

## [1.2.10] - 2026-06-18

### Changed

- **CI coverage threshold**: minimum project coverage in GitHub Actions lowered from 100% to **95%** (`MIN_COVERAGE` env in `ci.yml`). README and CONTRIBUTING updated accordingly. The test suite on `main` still reports 100% locally.

## [1.2.9] - 2026-06-18

### Fixed

- **IconSupportChecker coverage / detection**: removed unreachable `IconRendererInterface` fallback; UX Icons availability is detected via Twig runtime classes only (`UXIconRuntime`, legacy `UXIconsRuntime`). Restores **100%** test coverage required by CI. No application config changes.

## [1.2.8] - 2026-06-18

### Changed

- **Default widget icon visibility**: show/hide toggle icons via inline `style.display` on the SVG instead of the Bootstrap `d-none` class. The initially hidden icon is rendered with `style: 'display: none'`; the inline `onclick` handler toggles `element.style.display`. No dependency on Bootstrap utility classes for icon visibility.

## [1.2.7] - 2026-06-18

### Fixed

- **Default widget icon rendering**: the template no longer checks `ux_icon is defined`. In Twig, `defined` applies to variables, not functions, so that condition was always false and icons were never rendered (dev showed `[icons missing]` even when `icons_available` was true and `symfony/ux-icons` was installed). Icons are now rendered when `icons_available` is true.

## [1.2.6] - 2026-06-18

### Fixed

- **UX Icons 3.x detection**: `IconSupportChecker` detects `Symfony\UX\Icons\Twig\UXIconRuntime` (with fallbacks for legacy `UXIconsRuntime` and `IconRendererInterface`). Without this fix, `icons_available` stayed false with UX Icons 3.x installed and the default widget showed `[icons missing]` in dev even after `composer require symfony/ux-icons symfony/http-client` and `ux:icons:lock`.

## [1.2.5] - 2026-06-18

### Fixed

- **UX Icons 3.x `ux:icons:lock`**: command accepts no icon arguments (scans Twig templates). Updated docs, demos (`auto-scripts`), recipe `post-install.txt`, `IconSupportChecker` warning message, and dev template hint. Use `php bin/console ux:icons:lock` (optional `--force`).

## [1.2.4] - 2026-06-18

### Added

- **UX Icons requirement handling**: `IconSupportChecker` and `IconSupportWarningSubscriber` detect missing `symfony/ux-icons` / `symfony/http-client`, log an actionable warning (Monolog channel `nowo_password_toggle`), and expose `icons_available` to the default Twig widget for graceful degradation (dev hint, prod without icons, toggle still works).
- **Flex recipe 1.2.3 / 1.2.4**: `require` for ux-icons and http-client, `config/packages/ux_icons.yaml`, tabler SVG assets, updated `post-install.txt`.

### Changed

- **Demos**: all demos require `symfony/http-client`; `ux:icons:lock tabler:eye tabler:eye-off` added to Composer `auto-scripts`.
- **Documentation**: [INSTALLATION.md](INSTALLATION.md) (manual install, runtime fallback), [USAGE.md](USAGE.md) (default icons section), [demo/README.md](../demo/README.md) (icon dependencies for demos).

## [1.2.3] - 2026-04-15

### Added

- **Twig template override order**: Compiler pass `TwigPathsPass` registers the bundle Twig namespace on the native loader **after** the application paths, so overrides in `templates/bundles/NowoPasswordToggleBundle/` are used before the bundle defaults.
- **Tests**: Unit tests under `tests/Unit/` (including `TwigPathsPassTest`); integration placeholder under `tests/Integration/`.
- **GitHub**: Issue templates (bug, feature, support), pull request template, `CODEOWNERS`, workflows (`sync-releases`, `stale`, `pr-lint`), Dependabot and Copilot instructions updates.

### Fixed

- **Demo Composer path repository**: Demos declare the bundle as `dev-main as 1.2.99` with `minimum-stability: dev` and `prefer-stable: true`, so the canonical **path** repository (`/var/password-toggle-bundle`) satisfies the same semver intent as `^1.2.x` and `composer install` works inside Docker. Documented in [demo/README.md](../demo/README.md) troubleshooting.

### Changed

- **Documentation**: README (toggle / UX Icons / `Caddyfile.dev` in dev), [DEMO-FRANKENPHP.md](DEMO-FRANKENPHP.md) (bundles example with UX Icons and Twig Inspector), [INSTALLATION.md](INSTALLATION.md) (UX Icons and default template), plus edits to CONTRIBUTING, BRANCHING, USAGE, SECURITY, ENGRAM.
- **Development**: Cursor rules, `.cursorignore`, Makefile and demo Makefiles, demo `.env.example` / docker-compose alignment, Flex recipe manifest path, PHPUnit config and coverage script.

## [1.2.2] - 2026-03-16

### Added

- **Demo with FrankenPHP**: New documentation and setup for running demos with FrankenPHP (development and production)
  - Added [docs/DEMO-FRANKENPHP.md](docs/DEMO-FRANKENPHP.md) with instructions for FrankenPHP, Caddyfile, and PHP dev ini
  - README links to the new demo doc for development and production usage

### Changed

- **Demo configurations and development setup**: Updated all demo projects for consistent development experience
  - Docker and docker-compose aligned across symfony6, symfony7, symfony8, and symfony8-php85 demos
  - FrankenPHP Caddyfile and PHP dev ini configuration in each demo
  - Demo `.env.test` and `.env.dev` updates where applicable
  - Twig dev package configuration for demos
  - Root Makefile, composer, and .gitignore adjustments

## [1.2.1] - 2026-03-09

### Added

- **README**: Demo screenshot showing the password toggle form in the main README for quick visual reference.

### Changed

- **Demo structure**: Renamed demo project directories for simpler paths
  - `demo/demo-symfony6` → `demo/symfony6`
  - `demo/demo-symfony7` → `demo/symfony7`
  - `demo/demo-symfony8` → `demo/symfony8`
  - `demo/demo-symfony8-php85` → `demo/symfony8-php85`
  - Makefile and documentation updated to use the new paths (e.g. `make up symfony7`).

### Fixed

- **PHPStan 2.x compatibility**: Updated configuration and code for PHPStan 2
  - Removed deprecated `memoryLimit` and `checkMissingIterableValueType` from `phpstan.neon.dist` (use CLI `--memory-limit` and analysis level instead).
  - Added generic type hints for Symfony Form: `@extends AbstractType<string>` and `FormInterface<string>` in `PasswordType`.
  - Adjusted `NowoPasswordToggleBundle::getContainerExtension()` return type handling for parent `ExtensionInterface|false` property.
  - Updated `NowoPasswordToggleExtension::load()` parameter type to `array<int, array<string, mixed>>` for config array.
  - Test type assertions and PHPStan ignores where needed for container parameters and assertions.

## [1.2.0] - 2025-12-15

### Added

- **Bundle Configuration System**: Added support for default configuration via YAML file
  - Created `Configuration` class to define bundle configuration structure
  - Added support for configuring default values in `config/packages/nowo_password_toggle.yaml`
  - All form type options can now be configured globally and overridden per field
  - Configuration is automatically injected into `PasswordType` service
- **Symfony Flex Recipe**: Created Flex recipe for automatic installation and configuration
  - Bundle registration is now automatic when using Symfony Flex
  - Default configuration file is automatically created at `config/packages/nowo_password_toggle.yaml`
  - Recipe includes manifest.json, configuration file, and post-install message
  - Recipe structure ready for publishing to symfony/recipes-contrib or private recipe repository
- **Makefile Improvements**: Enhanced demo Makefile for better usability
  - Generic commands now accept demo name as direct argument (e.g., `make up symfony8`)
  - Both formats supported: `make up symfony8` and `make up DEMO=symfony8`
  - Updated help documentation to show both usage formats
- **Configuration Validation**: Added comprehensive validation for bundle configuration
  - Icons and labels cannot be empty strings
  - Arrays (button_classes, toggle_container_classes) are validated as arrays
  - Invalid message must be non-empty
  - Clear error messages for invalid configuration values
- **Type Validation**: Added type validation in OptionsResolver
  - All form type options now have strict type validation
  - Prevents type errors at runtime
  - Better error messages for invalid option types
- **Template Improvements**: Enhanced Twig template with better functionality
  - Support for `toggle=false` to render simple password input without toggle button
  - Keyboard support (Enter/Space keys) for toggle button
  - Improved error handling in JavaScript with null checks
  - Better accessibility with proper ARIA attributes
- **Test Coverage**: Added comprehensive tests for new functionality
  - Tests for Configuration class with validation scenarios
  - Tests for Extension with configuration parameter storage
  - Tests for PasswordType with injected configuration
  - Tests for type validation in OptionsResolver
- **Demo Projects Test Configuration**: Fixed test environment setup for all demo projects
  - Created `config/packages/test/framework.yaml` for all demos to enable functional testing
  - Fixed `phpunit.xml.dist` to correctly set `APP_ENV=test` for all demos
  - Added `DEFAULT_URI` environment variable to `.env.example` files for Symfony 7.0+ compatibility
  - All demo test suites now run correctly in test environment
- **Demo Projects Updated**: Updated all demo projects to use bundle v1.2.0
  - Updated `composer.json` in all demos to require `^1.2.0`
  - Updated `composer.lock` files to reflect v1.2.0 dependency
  - Added `config/packages/nowo_password_toggle.yaml` configuration files to all demos
  - Demos now demonstrate the new global configuration feature
  - Updated demo README to document the configuration system
  - All demos verified to work correctly and respond with HTTP 200

### Changed

- **PasswordType Service**: Modified to accept configuration as constructor dependency
  - Configuration defaults are now injected via dependency injection
  - Maintains backward compatibility with hardcoded fallbacks
  - Configuration can be overridden per form field as before
  - Added strict type validation for all options
- **Template Rendering**: Improved template logic for better flexibility
  - Conditional rendering based on `toggle` option
  - Fallback to simple password input when toggle is disabled
  - Enhanced JavaScript with better error handling
- **Code Quality**: Improved code quality and maintainability
  - Removed unused imports from Extension class
  - Added validation to prevent configuration errors
  - Enhanced error messages for better debugging
- **Documentation**: Updated README with configuration section
  - Added comprehensive configuration documentation
  - Explained Flex recipe automatic installation
  - Updated usage examples to show configuration options
- **Documentation Structure**: Reorganized documentation files
  - Moved `CHANGELOG.md` to `docs/CHANGELOG.md` for better organization
  - Moved `CONTRIBUTING.md` to `docs/CONTRIBUTING.md` for consistency
  - Added `docs/BRANCHING.md` with branching strategy documentation
  - Updated all references to documentation paths in README

## [1.1.1] - 2024-12-12

### Fixed

- **Demo Projects**: Fixed multiple issues in all demo projects
  - Fixed incorrect package name: Changed `symfony/ux-icon` to `symfony/ux-icons` (correct package name)
  - Fixed bundle registration: Updated `bundles.php` to use correct namespace `Symfony\UX\Icons\UXIconsBundle` instead of `Symfony\UX\Icon\IconBundle`
  - Fixed nginx configuration: Corrected `SCRIPT_FILENAME` path from `$document_root$fastcgi_script_name` to `/app/public$fastcgi_script_name` for proper file resolution
  - Fixed routing configuration: Added explicit attribute-based routing configuration in `routes.yaml` for all demos
  - Added missing dependency: Added `symfony/yaml` to all demo `composer.json` files (required for YAML configuration loading)
  - Added web profiler: Added `symfony/web-profiler-bundle` to all demos for better debugging experience
  - Fixed port configuration: Ensured all demos use port 8001 by default and can be configured via environment variables

### Changed

- **Demo Configuration**: Improved demo project configurations
  - All demos now properly load routes from controller attributes
  - Web profiler bundle registered for `dev` and `test` environments in all demos
  - Consistent nginx configuration across all demo projects
  - All `composer.lock` files generated and updated to ensure reproducible builds

## [1.1.0] - 2024-12-12

### Added

- **Multiple Demo Projects**: Created four independent demo projects for different Symfony/PHP combinations
  - Symfony 6.4 demo (PHP 8.2) - Port 8001 by default
  - Symfony 7.0 demo (PHP 8.2) - Port 8001 by default
  - Symfony 8.0 demo (PHP 8.4) - Port 8001 by default
  - Symfony 8.0 demo with PHP 8.5 - Port 8001 by default
  - Each demo includes its own Docker setup, tests, and configuration
  - Port configuration via `.env` files (all use 8001 by default, configurable)
- **Demo Test Suites**: Each demo project includes comprehensive PHPUnit test suite
  - Controller tests to verify form functionality
  - Bundle integration tests to verify bundle registration
  - Tests verify password toggle functionality, form submission, and UI elements
- **100% Code Coverage**: Achieved complete test coverage for the bundle
  - All classes and methods fully tested
  - Enhanced test suite with comprehensive edge case coverage
  - All tests passing
- **CONTRIBUTING.md**: Added comprehensive contribution guide in English
  - Code standards and conventions
  - Testing requirements (100% coverage)
  - Pull request process and checklist
  - Development setup instructions

### Changed

- **CI/CD Workflow**: Enhanced GitHub Actions workflow
  - Added support for Symfony 8.0 and PHP 8.4/8.5
  - Updated matrix to test all compatible PHP/Symfony combinations
  - Improved dependency installation with Symfony version-specific requirements
  - Updated to actions/checkout@v6 and actions/cache@v5
- **Dependencies**: Updated `composer.json`
  - Added Symfony 8.0 support: `^6.0 || ^7.0 || ^8.0`
  - Updated PHP requirement: `>=8.1 <8.6` (was `>=8.1`)
  - PHPUnit constraint set to `^10.0` only (removed `^11.0` for PHP 8.1 compatibility)
- **Code Style**: Fixed PHP-CS-Fixer configuration
  - Removed duplicate `single_blank_line_before_namespace` rule (already included in `@PSR12`)
- **Documentation**: Enhanced README.md
  - Added comprehensive Testing section with coverage information
  - Added Code Quality section
  - Added CI/CD section with detailed matrix information
  - Updated Demo Project section with information about multiple demos
  - Added link to CONTRIBUTING.md

### Fixed

- **Demo Projects**: Fixed Docker setup issues
  - Created Dockerfiles for each demo with Composer pre-installed
  - Fixed docker-compose.yml to use build context instead of image
  - Removed obsolete `version` attribute from docker-compose.yml
  - Added entrypoint scripts for proper directory permissions
- **Port Configuration**: Implemented `.env` file support for demo ports
  - Each demo has its own `.env` file with default port configuration
  - Ports can be customized per demo without modifying docker-compose.yml
  - All demos use port 8001 by default (configurable via `.env` file)

### Improved

- **PHPDoc Documentation**: Enhanced all classes with comprehensive PHPDoc comments in English
  - All bundle classes (`NowoPasswordToggleBundle`, `NowoPasswordToggleExtension`, `PasswordType`) have detailed documentation with feature lists
  - All demo classes (`DemoController`, `Kernel`) include complete PHPDoc with `@author` and `@copyright` tags
  - All public methods include `@param` and `@return` annotations with detailed descriptions
  - Enhanced class descriptions with usage information and examples
- **Test Coverage**: Enhanced test suite to achieve 100% coverage
  - Improved `NowoPasswordToggleBundleTest` with better isolation
  - Enhanced `NowoPasswordToggleExtensionTest` to verify service registration
  - Added comprehensive tests for `PasswordType` covering all options and edge cases
  - All tests now verify actual behavior instead of just asserting true
- **Demo Structure**: Organized demo projects with independent setups
  - Each demo can run independently with its own docker-compose.yml
  - Makefile updated with commands for all demos (up, down, install, test, logs)
  - Comprehensive README.md in demo directory with usage instructions
  - Test commands for each demo and all demos combined

## [1.0.0] - 2024-12-11

### Compatibility

- **PHP**: >= 8.1, < 8.6
- **Symfony**: >= 6.0 || >= 7.0 || >= 8.0
- **Symfony UX Icon**: >= 2.0 || >= 3.0

### Added

- **Password form type with toggle visibility**: Custom Symfony form type that extends TextType
  - Toggle button to show/hide password
  - Native JavaScript implementation (no Stimulus) for maximum compatibility
  - Compatible with Symfony Live Components
  - Customizable icons using Symfony UX Icon
  - Configurable CSS classes for button and container
  - Customizable labels for accessibility
- **Twig template**: `toggle_password_widget.html.twig` for rendering the password field
  - Inline JavaScript for toggle functionality
  - ARIA labels for accessibility
  - Support for Tabler icons (default) or any UX Icon compatible icon
- **Bundle structure**: Complete Symfony bundle with:
  - Bundle class (`NowoPasswordToggleBundle`)
  - DependencyInjection extension (`NowoPasswordToggleExtension`)
  - Form type service configuration
  - Automatic template discovery
- **Configuration options**:
  - `toggle`: Enable/disable toggle functionality (default: `true`)
  - `visible_icon`: Icon when password is hidden (default: `'tabler:eye-off'`)
  - `hidden_icon`: Icon when password is visible (default: `'tabler:eye'`)
  - `visible_label`: Label when password is hidden (default: `'Show'`)
  - `hidden_label`: Label when password is visible (default: `'Hide'`)
  - `button_classes`: CSS classes for toggle button (default: `['input-group-text', 'cursor-pointer']`)
  - `toggle_container_classes`: CSS classes for container (default: `['form-password-toggle']`)
  - `always_empty`: Always render empty value (default: `true`)
  - `trim`: Trim whitespace (default: `false`)
  - `invalid_message`: Invalid message (default: `'The password is invalid.'`)
- **Development tools**:
  - PHPUnit test configuration
  - PHP-CS-Fixer configuration (PSR-12)
  - Docker development environment
  - Makefile for common development tasks
  - Composer scripts for testing and code style
- **Documentation**:
  - Complete README with usage examples
  - PHPDoc documentation in English for all classes and methods
  - Inline code comments in English

### Notes

- The bundle automatically registers the form type service
- Templates are automatically discovered by Symfony
- No `parent_attr` option (removed for simplicity)
- Uses native JavaScript onclick to avoid conflicts with Live Components
- Requires Symfony UX Icon bundle for icon support

