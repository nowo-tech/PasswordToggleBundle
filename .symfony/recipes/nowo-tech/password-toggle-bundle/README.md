# Symfony Flex Recipe for Password Toggle Bundle

This directory contains the Symfony Flex recipe for the Password Toggle Bundle.

## What This Recipe Does

When the bundle is installed via Composer, this recipe will:

1. **Register the bundle** automatically in `config/bundles.php`
2. **Create the default configuration file** at `config/packages/nowo_password_toggle.yaml`

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

## Recipe Structure

```
1.1.1/
├── manifest.json                    # Recipe definition
├── config/
│   └── packages/
│       └── nowo_password_toggle.yaml  # Default configuration
└── post-install.txt                # Message shown after installation
```

## Versioning

Create a new recipe directory for each major/minor version:
- `1.1.1/` - Current version
- `1.2.0/` - Next minor version
- `2.0.0/` - Next major version

Each version can have different configuration defaults or installation steps.

