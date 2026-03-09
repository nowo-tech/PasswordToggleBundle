# Usage

## Basic usage

Use the `PasswordType` from the bundle in your form builders:

```php
use Nowo\PasswordToggleBundle\Form\Type\PasswordType;

$builder->add('password', PasswordType::class);
```

## With options

Override any default (from config) per field:

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

## Disabling the toggle

For a specific field, render a simple password input without the toggle button:

```php
$builder->add('password', PasswordType::class, [
    'toggle' => false,
]);
```

When `toggle` is `false`, the field renders as a standard password input, compatible with any styling or JavaScript framework.

## Styling

- **Option 1:** Include the bundle CSS:  
  `<link rel="stylesheet" href="{{ asset('bundles/nowopasswordtoggle/css/toggle_password.css') }}">`
- **Option 2:** Import the SCSS in your build (Webpack Encore, Vite, etc.):  
  `@import '@nowo-tech/password-toggle-bundle/src/Resources/public/css/toggle_password.scss';`
- **Option 3:** Style the classes yourself: `.input-group-text.cursor-pointer`, `.form-password-toggle`, etc.

See the main [README](../../README.md#styling) for more styling details.

## See also

- [Configuration](CONFIGURATION.md)
- [Installation](INSTALLATION.md)
