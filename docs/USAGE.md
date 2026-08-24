# Usage

## Table of contents

- [Basic usage](#basic-usage)
- [With options](#with-options)
- [Disabling the toggle](#disabling-the-toggle)
- [Default icons (UX Icons)](#default-icons-ux-icons)
- [Styling](#styling)
- [Overriding bundle templates](#overriding-bundle-templates)
- [See also](#see-also)

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

## Default icons (UX Icons)

The bundled widget `toggle_password_widget.html.twig` renders icons with `ux_icon()` (`tabler:eye-off` / `tabler:eye` by default). You need:

- **symfony/ux-icons** ^2.0 || ^3.0
- **symfony/http-client** (same Symfony major as your app)

Symfony Flex (recipe **1.2.3+**) adds both packages, copies starter SVGs to `assets/icons/tabler/`, and creates `config/packages/ux_icons.yaml`. After install, lock icons for production:

```bash
php bin/console ux:icons:lock
```

With **UX Icons 3.x**, this command scans Twig templates for `ux_icon()` usage (no icon names as CLI arguments). The recipe also ships local tabler SVGs as a fallback before the first lock.

**Without Flex**, install manually — see [Installation](INSTALLATION.md).

**If packages are missing:** the toggle still works; icons are omitted. In **dev** you may see `[icons missing]`; a one-time log warning points to `composer require symfony/ux-icons symfony/http-client`. No compile-time exception.

**Custom icons without UX Icons:** override `templates/bundles/NowoPasswordToggleBundle/Form/toggle_password_widget.html.twig` and replace `ux_icon()` with your markup (SVG, `<i>`, etc.).

## Styling

- **Option 1:** Include the bundle CSS:  
  `<link rel="stylesheet" href="{{ asset('css/toggle_password.css', 'nowo_password_toggle') }}">`
- **Web Component script:** the default widget loads `js/nowo-password-toggle.js` once per request. After `assets:install` you can also include it in the layout:

```twig
<script src="{{ asset('js/nowo-password-toggle.js', 'nowo_password_toggle') }}" defer></script>
```

The host tag is `<nowo-password-toggle>` (light DOM: native password input + toggle button). Inline `onclick` / `onkeydown` handlers are no longer used.
- **Option 2:** Import the SCSS in your build (Webpack Encore, Vite, etc.):  
  `@import '@nowo-tech/password-toggle-bundle/src/Resources/public/css/toggle_password.scss';`
- **Option 3:** Style the classes yourself: `.input-group-text.cursor-pointer`, `.form-password-toggle`, etc.

See the main [README](../../README.md#styling) for more styling details.

## Overriding bundle templates

The bundle registers its Twig views so that `@NowoPasswordToggleBundle/...` works, and it adds its view path **after** the application paths. Your overrides in **`templates/bundles/NowoPasswordToggleBundle/`** are therefore checked first.

**Using the bundle's form theme:** add the bundle's widget to your form themes (e.g. in `config/packages/twig.yaml`):

```yaml
twig:
  form_themes:
    - '@NowoPasswordToggleBundle/Form/toggle_password_widget.html.twig'
```

**To override:** create a file under `templates/bundles/NowoPasswordToggleBundle/` with the same relative path as in the bundle; Twig will use your copy instead of the bundle's.


**Example:** to override the password toggle widget, create `templates/bundles/NowoPasswordToggleBundle/Form/toggle_password_widget.html.twig`. You can copy the original from `vendor/nowo-tech/password-toggle-bundle/src/Resources/views/Form/toggle_password_widget.html.twig` and adjust as needed.

**Templates you can override:**

| Path (relative to `Resources/views/`) | Purpose |
|--------------------------------------|---------|
| `Form/toggle_password_widget.html.twig` | Form widget for the password field with visibility toggle. |

After adding or changing overrides, clear the Twig cache if needed: `php bin/console cache:clear`.

## See also

- [Configuration](CONFIGURATION.md)
- [Installation](INSTALLATION.md)
