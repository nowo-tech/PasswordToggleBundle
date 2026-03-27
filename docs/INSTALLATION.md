# Installation

This guide covers installing Password Toggle Bundle in a Symfony application.

## Requirements

- **PHP** >= 8.1, < 8.6
- **Symfony** ^6.0 || ^7.0 || ^8.0
- **symfony/form**, **symfony/framework-bundle**, **symfony/twig-bundle**
- **symfony/ux-icons** ^2.0 || ^3.0 — **required for the default Twig widget** (`ux_icon()`). The bundle only `suggest`s it; run `composer require symfony/ux-icons` unless you override the form theme to avoid `ux_icon()`.

## Install with Composer

```bash
composer require nowo-tech/password-toggle-bundle
```

Use a constraint such as `^1.0` to stay on the current major version.

## Register the bundle

### With Symfony Flex

If you use Symfony Flex, the bundle is registered automatically and a default configuration file is created at `config/packages/nowo_password_toggle.yaml`.

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

3. **Create configuration** (optional). Create `config/packages/nowo_password_toggle.yaml` with your preferred defaults. See [CONFIGURATION.md](CONFIGURATION.md) for all options.

## Next steps

- [Configuration](CONFIGURATION.md)
- [Usage](USAGE.md)
