# Password Toggle Bundle - Demo

`demo/symfony8` is the bundle demo: Symfony 8 on FrankenPHP **PHP 8.5** (the newest PHP allowed by `require.php`), a login form using the bundle's `PasswordType`, Bootstrap 5 and tabler icons. One demo covers the supported PHP range; the former `symfony8-php85` copy was merged into it.

## Table of contents

- [Requirements](#requirements)
- [Quick start with Docker](#quick-start-with-docker)
- [Make targets](#make-targets)
- [Port](#port)
- [How it works](#how-it-works)
- [Customization](#customization)
- [Troubleshooting](#troubleshooting)
- [License](#license)

## Requirements

- Docker with Docker Compose (V2 preferred, V1 fallback).

## Quick start with Docker

```bash
cd demo/symfony8
cp -n .env.example .env   # first run only
make up                   # FrankenPHP (worker mode by default) + composer install
# http://localhost:8003
```

From the bundle root you can also run `make -C demo up-symfony8` and `make demo-smoke` (boot + final HTTP 200). See [docs/DEMO-FRANKENPHP.md](../docs/DEMO-FRANKENPHP.md) for `FRANKENPHP_MODE` (worker / classic) and the worker audit.

## Make targets

| Target | What it does |
| --- | --- |
| `make -C demo/symfony8 up` / `down` | Start / stop the demo |
| `make -C demo/symfony8 test` | Demo PHPUnit suite |
| `make -C demo/symfony8 update-bundle` | Re-sync the bundle from the path repository and clear the cache |
| `make -C demo/symfony8 test-e2e` / `demo-screenshots` | Playwright e2e and the README screenshots (REQ-DEMO-013) |
| `make -C demo release-check` | update-bundle, demo tests, then release-verify (boot + HTTP 200) |

## Port

`PORT` in `demo/symfony8/.env` (default **8003**, from `.env.example`) is the host port mapped to FrankenPHP's port 80; `make up` prints `Demo started at: http://localhost:<PORT>`. Change it in `.env` and recreate the containers if 8003 is taken.

## How it works

The demo form uses the bundle's `PasswordType`:

```php
->add('password', PasswordType::class, [
    'label'         => 'Password',
    'toggle'        => true,
    'visible_icon'  => 'tabler:eye-off',
    'hidden_icon'   => 'tabler:eye',
    'visible_label' => 'Show password',
    'hidden_label'  => 'Hide password',
])
```

It renders the password input, a toggle button with eye icons, the Stimulus/JS behaviour that shows or hides the value, and ARIA labels.

## Customization

### Global Configuration

Each demo includes a `config/packages/nowo_password_toggle.yaml` file that demonstrates the bundle's configuration system (available since v1.2.0). This file defines default values for all password fields, which can be overridden per field when using the form type.

The configuration file includes:
- Default toggle settings
- Icon and label configuration
- CSS classes for buttons and containers
- Form type options

### Per-Field Customization

You can also customize the password field by modifying the options in each demo's `src/Controller/DemoController.php`:

- `toggle`: Enable/disable toggle functionality
- `visible_icon`: Icon when password is hidden
- `hidden_icon`: Icon when password is visible
- `visible_label`: Label when password is hidden
- `hidden_label`: Label when password is visible
- `button_classes`: CSS classes for the toggle button
- `toggle_container_classes`: CSS classes for the container

**Note**: Options specified in the controller override the global configuration from `nowo_password_toggle.yaml`.

## Troubleshooting

### Composer install fails

Make sure the bundle is properly linked. The demos use a path repository to link to the parent bundle. If you're running this outside the bundle directory, you may need to adjust the repository path in `composer.json`.

If Composer reports that the path repository has higher priority but `dev-main` does not satisfy your constraint: the demos require the bundle as `dev-main as 2.0.99` (inline alias) plus `minimum-stability: dev` and `prefer-stable: true`, so the mounted source satisfies the same semver range as a tagged release. Without that, Composer prefers the canonical path package and cannot merge it with the Packagist constraint.

### Toggle icons not showing

Demos require **symfony/ux-icons** and **symfony/http-client** (matching the demo Symfony major). After `composer install`, lock the default tabler icons:

```bash
docker-compose exec php php bin/console ux:icons:lock
```

Composer `auto-scripts` in each demo run this on `post-install-cmd` / `post-update-cmd`. If icons are still missing, ensure `assets/icons/tabler/eye.svg` and `eye-off.svg` exist (copy from `.symfony/recipes/nowo-tech/password-toggle-bundle/1.2.4/assets/icons/tabler/` in the bundle repo) and check `config/packages/ux_icons.yaml`.

### Routes not loading

If routes are not loading, verify:
- The `routes.yaml` file includes the controllers configuration for attribute-based routing
- The controller uses the `#[Route]` attribute correctly
- The cache is cleared after configuration changes

## License

MIT — see the bundle [LICENSE](../LICENSE).
