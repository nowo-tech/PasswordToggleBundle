# Code inventory — 100% traceability

**Baseline spec**: [`spec.md`](spec.md)  
**Package**: `nowo-tech/password-toggle-bundle`  
**Last audited**: 2026-11-16 (2.7.0)

This file proves that **every production source artifact** under `src/` is referenced by the baseline specification. Test-only files under `tests/` and demo trees are out of Packagist scope unless promoted in the spec.

## PHP classes (`src/**/*.php`)

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `NowoPasswordToggleBundle.php` | Bundle entry | FR-BUNDLE-001 |
| `DependencyInjection/Configuration.php` | Config tree (icons, CSS, labels) | FR-CFG-001 |
| `DependencyInjection/NowoPasswordToggleExtension.php` | DI extension | FR-CFG-002 |
| `DependencyInjection/Compiler/TwigPathsPass.php` | Twig namespace | FR-TWIG-001 |
| `Form/Type/PasswordType.php` | Password field with visibility toggle | FR-FORM-001, REQ-FP-001 |
| `Form/Extension/CorePasswordTypeExtension.php` | Opt-in toggle on Symfony's core `PasswordType` (2.7) | FR-FORM-002 |
| `Form/ToggleLabels.php` | Show / hide labels, translated when a domain is set (2.7) | FR-I18N-001 |
| `IconSupport/IconSupportChecker.php` | UX Icons availability check | FR-ICON-001 |
| `EventSubscriber/IconSupportWarningSubscriber.php` | Warn when icons missing | FR-ICON-002, REQ-FP-001 |

## Assets & Twig (`src/Resources/`)

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `Resources/config/services.yaml` | Service wiring | FR-DI-001 |
| `Resources/views/Form/toggle_password_widget.html.twig` | Toggle widget: web component (nonce) / Stimulus / none | FR-TWIG-002, FR-CSP-001, FR-CSP-002 |
| `Resources/public/css/toggle_password.css` | Compiled styles | FR-ASSET-001 |
| `Resources/public/css/toggle_password.scss` | Source styles | FR-ASSET-001 |
| `Resources/public/js/nowo-password-toggle.js` | CSP-safe custom element + delegation | FR-ASSET-002, FR-TWIG-002 |
| `../assets/controllers/password_toggle_controller.js` | Optional Stimulus controller | FR-CSP-002 |

## Translations (`src/Resources/translations/`)

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `Resources/translations/NowoPasswordToggleBundle.en.yaml` | Toggle labels (en) | FR-I18N-002 |
| `Resources/translations/NowoPasswordToggleBundle.de.yaml` | Toggle labels (de) | FR-I18N-002 |
| `Resources/translations/NowoPasswordToggleBundle.es.yaml` | Toggle labels (es) | FR-I18N-002 |
| `Resources/translations/NowoPasswordToggleBundle.fr.yaml` | Toggle labels (fr) | FR-I18N-002 |
| `Resources/translations/NowoPasswordToggleBundle.it.yaml` | Toggle labels (it) | FR-I18N-002 |
| `Resources/translations/NowoPasswordToggleBundle.nl.yaml` | Toggle labels (nl) | FR-I18N-002 |
| `Resources/translations/NowoPasswordToggleBundle.pt.yaml` | Toggle labels (pt) | FR-I18N-002 |

## Coverage summary

| Category | Files | Mapped |
| --- | ---: | ---: |
| PHP classes | 9 | 9 |
| Assets & Twig | 5 | 5 |
| Translations | 7 | 7 |
| **Total production sources** | **21** | **21** |
