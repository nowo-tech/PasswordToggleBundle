# Feature Specification: PasswordToggleBundle baseline (100% code coverage)

**Feature Branch**: `001-baseline`  
**Created**: 2026-07-07  
**Status**: Active  
**Input**: Backfill GitHub Spec Kit baseline documenting 100% of production code in `src/`.

**Related docs**: [`docs/SPEC-DRIVEN-DEVELOPMENT.md`](../../docs/SPEC-DRIVEN-DEVELOPMENT.md), [`docs/CONFIGURATION.md`](../../docs/CONFIGURATION.md), [`docs/USAGE.md`](../../docs/USAGE.md)  
**Code inventory (traceability)**: [`code-inventory.md`](code-inventory.md)

---

## Summary

**Package**: `nowo-tech/password-toggle-bundle`  
**Configuration root**: `nowo_password_toggle`

Symfony `PasswordType` form field with **show/hide** toggle using inline JavaScript (Live Component safe), optional Symfony UX Icons, configurable CSS classes, and ARIA labels.


## Notes / release sync

- **2026-09-03:** Toggle host is `<nowo-password-toggle>` with CSP-safe event delegation (no inline onclick/onkeydown).

---

## User Scenarios & Testing

### User Story 1 — Toggle password visibility (Priority: P1)

As a form author, I use `Nowo\PasswordToggleBundle\Form\Type\PasswordType` so users can reveal passwords to verify typing.

**Independent Test**: Render password field → click toggle → input `type` switches between `password` and `text`; button labels/icons swap.

**Acceptance Scenarios**:

1. **Given** `toggle=true`, **When** widget renders, **Then** toggle button appears beside input with `visible_label` when masked.
2. **Given** user clicks toggle, **When** password visible, **Then** `hidden_label` and `hidden_icon` apply and focus remains accessible.
3. **Given** `toggle=false` on field, **When** rendered, **Then** standard password input without button.

---

### User Story 2 — Live Component compatibility (Priority: P1)

As an integrator using Symfony UX Live Components, I need inline handlers without Stimulus controllers on the widget.

**Independent Test**: Embed field in Live Component form → toggle works after re-render without Stimulus registration.

**Acceptance Scenarios**:

1. **Given** `toggle_password_widget.html.twig`, **When** rendered, **Then** visibility toggled via inline `onclick`/`onkeydown` only.
2. **Given** Live Component partial update, **When** DOM replaced, **Then** toggle still functions on new nodes (no global controller state).

---

### User Story 3 — UX Icons with graceful fallback (Priority: P2)

As an integrator, I want Tabler icons when `symfony/ux-icons` is installed and text-only fallback otherwise.

**Acceptance Scenarios**:

1. **Given** UX Icons available, **When** `IconSupportChecker` returns true, **Then** Twig renders icon UX components for show/hide states.
2. **Given** UX Icons absent, **When** bundle boots, **Then** `IconSupportWarningSubscriber` logs one-time warning and widget uses labels only.
3. **Given** custom `visible_icon` / `hidden_icon` in config, **When** icons enabled, **Then** configured icon names used.

---

### User Story 4 — Configure defaults globally (Priority: P2)

As an integrator, I set bundle-wide defaults in YAML and override per field in form builders.

**Acceptance Scenarios**:

1. **Given** `config/packages/nowo_password_toggle.yaml`, **When** extension loads, **Then** defaults injected into `PasswordType` constructor.
2. **Given** per-field options in form builder, **When** set, **Then** they override bundle defaults for that field.
3. **Given** invalid empty icon/label in config, **When** tree validated, **Then** configuration validation fails at compile time.

---

### Edge Cases

- Keyboard: toggle button activatable via Enter/Space (`onkeydown`).
- `always_empty=true` (default): field renders empty on validation errors (security).
- `trim=false` (default): whitespace preserved in password values.
- SCSS source compiled to CSS for consumers without Sass pipeline.

---

## Requirements

### Bundle & DI

- **FR-BUNDLE-001**: `NowoPasswordToggleBundle` MUST register `TwigPathsPass` and expose alias `nowo_password_toggle`.
- **FR-DI-001**: `services.yaml` MUST wire `PasswordType` with config defaults and `IconSupportChecker`.
- **FR-CFG-001**: `Configuration` MUST define: `toggle`, `visible_icon`, `hidden_icon`, `visible_label`, `hidden_label`, `button_classes`, `toggle_container_classes`, `use_toggle_form_theme`, `always_empty`, `trim`, `invalid_message` with non-empty validation on strings.
- **FR-CFG-002**: `NowoPasswordToggleExtension` MUST load services, merge config into form type defaults, and register form theme when `use_toggle_form_theme=true`.
- **FR-TWIG-001**: `TwigPathsPass` MUST register bundle views namespace for theme overrides.

### Form type

- **FR-FORM-001**: `PasswordType` MUST extend `TextType`, set `type=password` by default, expose toggle/icon/label/class options, inject `IconSupportChecker`, and pass vars to Twig widget block.

### Icons & warnings

- **FR-ICON-001**: `IconSupportChecker` MUST detect whether Symfony UX Icons Twig functions are callable.
- **FR-ICON-002**: `IconSupportWarningSubscriber` MUST log a single warning per request lifecycle when icons expected but unavailable.

### Widget & assets

- **FR-TWIG-002**: `toggle_password_widget.html.twig` MUST render input group with toggle button, ARIA attributes, inline JS toggle function, and optional UX icon partials.
- **FR-ASSET-001**: `toggle_password.scss` / `toggle_password.css` MUST style container, button hover/focus rings, and input-group alignment for accessibility.

---

## Success Criteria

- **SC-001**: **11/11** production files mapped in [`code-inventory.md`](code-inventory.md).
- **SC-002**: Toggle works in demo with Live Components and keyboard navigation.
- **SC-003**: PHPUnit + PHPStan pass (`composer qa`).
- **SC-004**: Widget functions without Stimulus or external JS bundles.
- **SC-005**: Config validation rejects empty icon names and labels at compile time.

---

## Configuration reference (normative defaults)

| Key | Default | Behavior |
| --- | --- | --- |
| `toggle` | `true` | Show toggle button |
| `visible_icon` | `tabler:eye-off` | Icon when password hidden |
| `hidden_icon` | `tabler:eye` | Icon when password visible |
| `visible_label` | `Show` | ARIA/text when hidden |
| `hidden_label` | `Hide` | ARIA/text when visible |
| `button_classes` | `input-group-text`, `cursor-pointer` | Toggle button CSS |
| `toggle_container_classes` | `form-password-toggle` | Wrapper CSS |
| `use_toggle_form_theme` | `true` | Auto-register form theme |
| `always_empty` | `true` | Clear value on re-render |
| `trim` | `false` | Preserve whitespace |

---

## Explicit non-goals

- Password strength or policy enforcement.
- Stimulus controller requirement.
- Custom icon rendering beyond UX Icons contract.
- Demo-only behavior unless documented as stable API.

---

## Validation

| Check | Command |
| --- | --- |
| Full QA | `composer qa` or `make release-check` |
| PHP tests | `vendor/bin/phpunit` |
| Static analysis | `vendor/bin/phpstan analyse` |
| Manual | Demo Live Component toggle + keyboard |

When changing behavior, update this spec, `code-inventory.md`, tests, and integrator docs.
