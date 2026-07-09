# Code inventory — 100% traceability

**Baseline spec**: [`spec.md`](spec.md)  
**Package**: `nowo-tech/password-toggle-bundle`  
**Last audited**: 2026-07-07

This file proves that **every production source artifact** under `src/` is referenced by the baseline specification. Test-only files under `tests/` and demo trees are out of Packagist scope unless promoted in the spec.

## PHP classes (`src/**/*.php`)

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `NowoPasswordToggleBundle.php` | Bundle entry | FR-BUNDLE-001 |
| `DependencyInjection/Configuration.php` | Config tree (icons, CSS, labels) | FR-CFG-001 |
| `DependencyInjection/NowoPasswordToggleExtension.php` | DI extension | FR-CFG-002 |
| `DependencyInjection/Compiler/TwigPathsPass.php` | Twig namespace | FR-TWIG-001 |
| `Form/Type/PasswordType.php` | Password field with visibility toggle | FR-FORM-001 |
| `IconSupport/IconSupportChecker.php` | UX Icons availability check | FR-ICON-001 |
| `EventSubscriber/IconSupportWarningSubscriber.php` | Warn when icons missing | FR-ICON-002 |

## Assets & Twig (`src/Resources/`)

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `Resources/config/services.yaml` | Service wiring | FR-DI-001 |
| `Resources/views/Form/toggle_password_widget.html.twig` | Inline toggle widget (no Stimulus) | FR-TWIG-002 |
| `Resources/public/css/toggle_password.css` | Compiled styles | FR-ASSET-001 |
| `Resources/public/css/toggle_password.scss` | Source styles | FR-ASSET-001 |

## Coverage summary

| Category | Files | Mapped |
| --- | ---: | ---: |
| PHP classes | 7 | 7 |
| Assets & Twig | 4 | 4 |
| **Total production sources** | **11** | **11** |
