# Password Toggle Bundle

[![CI](https://github.com/nowo-tech/password-toggle-bundle/actions/workflows/ci.yml/badge.svg)](https://github.com/nowo-tech/password-toggle-bundle/actions/workflows/ci.yml)
[![Latest Stable Version](https://poser.pugx.org/nowo-tech/password-toggle-bundle/v)](https://packagist.org/packages/nowo-tech/password-toggle-bundle)
[![License](https://poser.pugx.org/nowo-tech/password-toggle-bundle/license)](https://packagist.org/packages/nowo-tech/password-toggle-bundle)
[![PHP Version Require](https://poser.pugx.org/nowo-tech/password-toggle-bundle/require/php)](https://packagist.org/packages/nowo-tech/password-toggle-bundle)

Symfony bundle providing a password form type with toggle visibility feature.

## Features

- ✅ Password form type with toggle visibility
- ✅ Customizable icons and labels
- ✅ JavaScript-free toggle (uses native onclick)
- ✅ Compatible with Symfony UX Icon
- ✅ Fully configurable CSS classes
- ✅ Works with Live Components
- ✅ Accessibility support (ARIA labels)

## Installation

```bash
composer require nowo-tech/password-toggle-bundle
```

Then, register the bundle in your `config/bundles.php`:

```php
<?php

return [
    // ...
    Nowo\PasswordToggleBundle\NowoPasswordToggleBundle::class => ['all' => true],
];
```

## Usage

### Basic Usage

```php
use Nowo\PasswordToggleBundle\Form\Type\PasswordType;

$builder->add('password', PasswordType::class);
```

### With Options

```php
$builder->add('password', PasswordType::class, [
    'toggle' => true,
    'visible_icon' => 'tabler:eye-off',
    'hidden_icon' => 'tabler:eye',
    'visible_label' => 'Show',
    'hidden_label' => 'Hide',
    'button_classes' => ['input-group-text', 'cursor-pointer'],
    'toggle_container_classes' => ['form-password-toggle'],
]);
```

### Available Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `toggle` | `bool` | `true` | Enable/disable toggle functionality |
| `visible_icon` | `string` | `'tabler:eye-off'` | Icon when password is hidden |
| `hidden_icon` | `string` | `'tabler:eye'` | Icon when password is visible |
| `visible_label` | `string` | `'Show'` | Label when password is hidden |
| `hidden_label` | `string` | `'Hide'` | Label when password is visible |
| `button_classes` | `array` | `['input-group-text', 'cursor-pointer']` | CSS classes for toggle button |
| `toggle_container_classes` | `array` | `['form-password-toggle']` | CSS classes for container |
| `always_empty` | `bool` | `true` | Always render empty value |
| `trim` | `bool` | `false` | Trim whitespace |
| `invalid_message` | `string` | `'The password is invalid.'` | Invalid message |

## Requirements

- PHP >= 8.1, < 8.6
- Symfony >= 6.0 || >= 7.0 || >= 8.0
- Symfony UX Icon >= 2.0 || >= 3.0 (for icon support)
- Bootstrap 5 (recommended for styling, but not required)

## Styling

The bundle includes CSS/SCSS styles for the toggle button. You can use them by:

### Option 1: Include the CSS file

```html
<link rel="stylesheet" href="{{ asset('bundles/nowopasswordtoggle/css/toggle_password.css') }}">
```

### Option 2: Use the SCSS file

If you're using a build system (Webpack Encore, Vite, etc.), import the SCSS:

```scss
@import '@nowo-tech/password-toggle-bundle/src/Resources/public/css/toggle_password.scss';
```

### Option 3: Custom styles

The bundle uses these CSS classes that you can style:
- `.input-group.input-group-merge` - Container
- `.input-group-text.cursor-pointer` - Toggle button
- `.icon-base` - Icon classes

Example custom styles:

```css
.input-group-text.cursor-pointer {
  cursor: pointer;
  user-select: none;
  transition: all 0.2s ease-in-out;
}

.input-group-text.cursor-pointer:hover {
  background-color: var(--bs-secondary-bg, #f8f9fa);
}

.input-group-text.cursor-pointer:active {
  transform: scale(0.95);
}

.input-group-text.cursor-pointer:focus-visible {
  outline: 2px solid var(--bs-primary, #696cff);
  outline-offset: 2px;
}
```

## Demo Projects

The bundle includes four demo projects demonstrating usage with different Symfony and PHP versions:

- **Symfony 6.4 Demo** (PHP 8.2) - Port 8001 (default, configurable via `.env`)
- **Symfony 7.0 Demo** (PHP 8.2) - Port 8001 (default, configurable via `.env`)
- **Symfony 8.0 Demo** (PHP 8.4) - Port 8001 (default, configurable via `.env`)
- **Symfony 8.0 Demo with PHP 8.5** - Port 8001 (default, configurable via `.env`)

Each demo is independent and includes:
- Complete Docker setup with PHP-FPM and Nginx
- Comprehensive test suite
- Port configuration via `.env` file

### Quick Start with Docker

```bash
cd demo
make up-symfony7        # Start Symfony 7.0 demo
make install-symfony7   # Install dependencies
# Access at: http://localhost:8001 (default port, configurable via .env)
```

Or start any other demo:

```bash
make up-symfony6        # Symfony 6.4
make up-symfony8        # Symfony 8.0
make up-symfony8-php85  # Symfony 8.0 with PHP 8.5
```

See `demo/README.md` for detailed instructions for all demos.

## Development

### Using Docker (Recommended)

```bash
# Start the container
make up

# Install dependencies
make install

# Run tests
make test

# Run tests with coverage
make test-coverage

# Run all QA checks
make qa
```

### Without Docker

```bash
composer install
composer test
composer test-coverage
composer qa
```

## Testing

The bundle has **100% code coverage** (all lines, methods, and classes). All tests are located in the `tests/` directory.

### Running Tests

```bash
# Run all tests
composer test

# Run tests with coverage report
composer test-coverage

# View coverage report
open coverage/index.html
```

### Test Structure

- `tests/NowoPasswordToggleBundleTest.php` - Bundle class tests
- `tests/DependencyInjection/` - Extension tests
- `tests/Form/` - Form type tests

All classes and methods are fully tested with 100% code coverage.

## Code Quality

The bundle uses PHP-CS-Fixer to enforce code style (PSR-12).

```bash
# Check code style
composer cs-check

# Fix code style
composer cs-fix
```

## CI/CD

The bundle uses GitHub Actions for continuous integration:

- **Tests**: Runs on PHP 8.1, 8.2, 8.3, 8.4, and 8.5 with Symfony 6.4, 7.0, and 8.0
  - PHP 8.1: Symfony 6.4 only (Symfony 7.0+ requires PHP 8.2+, Symfony 8.0 requires PHP 8.4+)
  - PHP 8.2 and 8.3: Symfony 6.4 and 7.0 (Symfony 8.0 requires PHP 8.4+)
  - PHP 8.4 and 8.5: All Symfony versions (6.4, 7.0, 8.0)
- **Code Style**: Automatically fixes code style on push to main/master
- **Code Style Check**: Validates code style on pull requests
- **Coverage**: Validates 100% code coverage requirement
- **Dependabot**: Automatically updates dependencies

See `.github/workflows/ci.yml` for details.

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on how to contribute to this project.

## Author

Created by [Héctor Franco Aceituno](https://github.com/HecFranco) at [Nowo.tech](https://nowo.tech)
