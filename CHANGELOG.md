# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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

