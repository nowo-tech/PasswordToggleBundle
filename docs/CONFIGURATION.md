# Configuration

The bundle is configured under the root key `nowo_password_toggle`. All options can be set globally here or overridden per field when using the form type.

## Configuration file

When installed via Symfony Flex, a default file is created at `config/packages/nowo_password_toggle.yaml`. Otherwise create it manually.

## Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `toggle` | `bool` | `true` | Enable/disable toggle functionality. When `false`, renders a simple password input without toggle button. |
| `visible_icon` | `string` | `'tabler:eye-off'` | Icon when password is hidden (must be non-empty). |
| `hidden_icon` | `string` | `'tabler:eye'` | Icon when password is visible (must be non-empty). |
| `visible_label` | `string` | `'Show'` | Label when password is hidden (must be non-empty). |
| `hidden_label` | `string` | `'Hide'` | Label when password is visible (must be non-empty). |
| `button_classes` | `array` | `['input-group-text', 'cursor-pointer']` | CSS classes for toggle button. |
| `toggle_container_classes` | `array` | `['form-password-toggle']` | CSS classes for container. |
| `use_toggle_form_theme` | `bool` | `true` | Use the bundle's form theme for rendering. |
| `always_empty` | `bool` | `true` | Always render empty value. |
| `trim` | `bool` | `false` | Trim whitespace. |
| `invalid_message` | `string` | `'The password is invalid.'` | Invalid message (must be non-empty). |
| `javascript` | `string` | `'web_component'` | How behaviour is attached (all CSP-safe, no inline handlers): `web_component` = widget emits one `<script src>` per request for `nowo-password-toggle.js` carrying the CSP nonce; `stimulus` = no `<script>`, renders `data-controller` / `data-action` for the shipped Stimulus controller; `none` = no `<script>`, load the JS yourself. |
| `stimulus_controller` | `string` | `'nowo-password-toggle'` | Stimulus identifier used when `javascript: stimulus`. |
| `csp_nonce_attribute` | `string\|null` | `'csp_nonce'` | Request attribute holding the CSP nonce; added as `nonce="…"` to the emitted `<script>`. `''`/`null` disables. |

## Example

```yaml
# config/packages/nowo_password_toggle.yaml
nowo_password_toggle:
    toggle: true
    visible_icon: 'tabler:eye-off'
    hidden_icon: 'tabler:eye'
    visible_label: 'Show'
    hidden_label: 'Hide'
    button_classes: ['input-group-text', 'cursor-pointer']
    toggle_container_classes: ['form-password-toggle']
    use_toggle_form_theme: true
    always_empty: true
    trim: false
    invalid_message: 'The password is invalid.'
    javascript: web_component        # web_component | stimulus | none
    stimulus_controller: nowo-password-toggle
    csp_nonce_attribute: csp_nonce
```

`javascript`, `stimulus_controller` and `csp_nonce_attribute` are also form options (per-field override). See [Usage → Content Security Policy](USAGE.md#content-security-policy-csp).

All options are validated for correct types. Invalid values will throw exceptions with clear error messages.

## See also

- [Usage](USAGE.md) for per-field overrides.
