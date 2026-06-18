# Symfony Flex Recipe for Password Toggle Bundle

This directory contains the Symfony Flex recipe for the Password Toggle Bundle.

## What This Recipe Does

When the bundle is installed via Composer, this recipe will:

1. **Register the bundle** automatically in `config/bundles.php`
2. **Create the default configuration file** at `config/packages/nowo_password_toggle.yaml`
3. **Require** `symfony/ux-icons` and `symfony/http-client` (default widget icons)
4. **Copy** `config/packages/ux_icons.yaml` and tabler SVG icons under `assets/icons/tabler/`

## Recipe Structure

```
1.2.3/
├── manifest.json
├── config/packages/nowo_password_toggle.yaml
├── config/packages/ux_icons.yaml
├── assets/icons/tabler/eye.svg
├── assets/icons/tabler/eye-off.svg
└── post-install.txt
```

## Versioning

Create a new recipe directory for each major/minor version:
- `1.1.1/` - Legacy (bundle + config only)
- `1.2.3/` - UX Icons, HTTP Client, locked icon assets
- `1.2.4/` - Same as 1.2.3 (recipe alias for bundle 1.2.4+)
- `2.0.0/` - Next major version

Each version can have different configuration defaults or installation steps.

## Publishing the Recipe

To make this recipe available to users, you need to publish it to a Flex recipe repository:

### Option 1: Public Recipe (Recommended for open-source bundles)

Publish to `symfony/recipes-contrib`:

1. Fork the [symfony/recipes-contrib](https://github.com/symfony/recipes-contrib) repository
2. Copy the recipe directory to `contrib/nowo-tech/password-toggle-bundle/1.1.1/`
3. Create a pull request

### Option 2: Private Recipe Repository

For private bundles, set up your own recipe repository:

1. Create a repository with the structure: `recipes/nowo-tech/password-toggle-bundle/1.1.1/`
2. Configure it in your project's `composer.json`:

```json
{
    "extra": {
        "symfony": {
            "allow-contrib": false,
            "require": "nowo-tech/recipes"
        }
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/nowo-tech/recipes"
        }
    ]
}
```

