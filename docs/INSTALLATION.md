# Installation

This guide covers installing Password Toggle Bundle in a Symfony application.

## Requirements

- **PHP** >= 8.2, < 8.6
- **Symfony** ^7.0 || ^8.0
- **symfony/form**, **symfony/framework-bundle**, **symfony/twig-bundle**
- **symfony/ux-icons** ^2.0 || ^3.0 and **symfony/http-client** (matching your Symfony major) — **required for the default Twig widget** (`ux_icon()` with `tabler:eye` / `tabler:eye-off`). The bundle only lists them as `suggest`; Symfony Flex installs both automatically via the recipe. Without Flex:

```bash
composer require symfony/ux-icons symfony/http-client
php bin/console ux:icons:lock
```

## Install with Composer

```bash
composer require nowo-tech/password-toggle-bundle
```

Use a constraint such as `^1.0` to stay on the current major version.

## Register the bundle

### With Symfony Flex

If you use Symfony Flex, the bundle is registered automatically, configuration is created at `config/packages/nowo_password_toggle.yaml`, **symfony/ux-icons** and **symfony/http-client** are added, and tabler icons are copied under `assets/icons/tabler/`. Run after install:

```bash
php bin/console ux:icons:lock
```

With **UX Icons 3.x**, the command scans Twig templates (no icon names as arguments). Use `--force` to overwrite existing local SVGs.

### Manual registration

1. **Register the bundle** in `config/bundles.php`:

```php
<?php

return [
    // ...
    Nowo\PasswordToggleBundle\NowoPasswordToggleBundle::class => ['all' => true],
];
```

2. **Add the form theme** so the password toggle widget is used. In `config/packages/twig.yaml` add the bundle's widget to `form_themes`:

   ```yaml
   twig:
     form_themes:
       - '@NowoPasswordToggleBundle/Form/toggle_password_widget.html.twig'
   ```

   You can override this template in your app by placing a copy in `templates/bundles/NowoPasswordToggleBundle/Form/toggle_password_widget.html.twig`. See [USAGE.md](USAGE.md#overriding-bundle-templates).

3. **Install icon dependencies** (required for the default widget):

   ```bash
   composer require symfony/ux-icons symfony/http-client
   ```

   Add `config/packages/ux_icons.yaml` (or equivalent):

   ```yaml
   ux_icons:
       iconify:
           enabled: true
       ignore_not_found: '%kernel.debug%'
   ```

   Copy tabler SVGs under `assets/icons/tabler/` (see the Flex recipe in `.symfony/recipes/.../1.2.3/assets/icons/tabler/`) or lock icons after install:

   ```bash
   php bin/console ux:icons:lock
   ```

4. **Create configuration** (optional). Create `config/packages/nowo_password_toggle.yaml` with your preferred defaults. See [CONFIGURATION.md](CONFIGURATION.md) for all options.

5. **Publish optional CSS** (if you use the legacy stylesheet instead of your own styles):

   ```bash
   php bin/console assets:install
   ```

   ```twig
   <link rel="stylesheet" href="{{ asset('css/toggle_password.css', 'nowo_password_toggle') }}">
   ```

   The default widget also loads `js/nowo-password-toggle.js` (custom element `<nowo-password-toggle>`). Run `assets:install` so both CSS and JS are published under `public/bundles/nowopasswordtoggle/`.

   ### AssetMapper

   If your app uses [Symfony AssetMapper](https://symfony.com/doc/current/frontend/asset_mapper.html), the bundle registers the `nowo_password_toggle` asset package. Run `assets:install` once so `css/toggle_password.css` is published to `public/bundles/nowopasswordtoggle/`.

## Missing icon packages (runtime behaviour)

If `symfony/ux-icons` or `symfony/http-client` is not installed:

- The bundle **does not** throw during `cache:warmup` or container compile.
- A **one-time warning** is logged (Monolog channel `nowo_password_toggle` when Monolog is present) with the exact `composer require` and `ux:icons:lock` commands.
- The default widget **degrades gracefully**:
  - **dev**: toggle works; a small `[icons missing]` hint is shown instead of icons.
  - **prod**: toggle works without icons (no uncaught exception from `ux_icon()`).

To restore icons, install both packages and run `php bin/console ux:icons:lock`, or override the form theme to avoid `ux_icon()`.

## Twig Extra Bundle (REQ-TWIG-004)

This package ships Twig templates. Host applications **must** install and enable Twig Extra:

```bash
composer require twig/extra-bundle twig/string-extra
```

Register `Twig\Extra\TwigExtraBundle\TwigExtraBundle` in `config/bundles.php` (Flex usually does this). Demos already include the same stack. The package `release-check` runs `make check-twig-extra` to guard this contract.

## Next steps

- [Configuration](CONFIGURATION.md)
- [Usage](USAGE.md)
