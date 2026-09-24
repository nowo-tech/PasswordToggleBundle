# FrankenPHP worker mode audit (kernel not reset between requests)

| Field | Value |
|-------|-------|
| Package | `nowo-tech/password-toggle-bundle` (`symfony-bundle`) |
| Audited revision | `v2.2.1` (post–`v2.2.0` web component) |
| Audit date | 2026-09-24 |
| Target runtime | FrankenPHP **worker** with **`FRANKENPHP_RESET_KERNEL=false`** (sticky Kernel / “Friendly Worker”) |
| Method | Manual review of every file under `src/` (form type, event subscriber, icon support checker, DI extension, configuration, compiler pass, `services.yaml`, Twig widget, public JS) + PHPStan classic + worker-strict + hardening |
| **Verdict** | ✅ **100% compatible** under Scenario B (`reset_kernel: false`) — no remediations required for sticky Kernel |

## Execution model assumed

FrankenPHP worker mode boots the Symfony kernel once per worker and serves many requests with the same container. This audit assumes the **strict** host contract used by Nowo “Friendly Worker” / kernel-isolation E2E:

| Host flag | Meaning |
|-----------|---------|
| `FRANKENPHP_MODE=worker` | Worker keeps the app in memory |
| **`FRANKENPHP_RESET_KERNEL=false`** | Kernel is **not** rebooted; Scenario **B** below |
| `FRANKENPHP_WORKER_NUM=1` | Single worker (isolation tests) |

Two scenarios are evaluated:

- **A — kernel not rebooted, `services_resetter` still runs:** services tagged `kernel.reset` (or implementing `ResetInterface`) are reset between requests. Typical default when `FRANKENPHP_RESET_KERNEL` is truthy / Runtime `worker=2`-style reset.
- **B — no reset at all (`FRANKENPHP_RESET_KERNEL=false`):** nothing is reset; any per-request state kept in a shared service leaks into the next request.

A bundle that is safe under **B** is safe under **A** and under classic mode / PHP-FPM.

## Summary

| Area | Status | Notes |
|------|--------|-------|
| Mutable state in shared services | ✅ | `PasswordType` uses `readonly` constructor defaults; only `IconSupportWarningSubscriber::$warned` (one-shot log guard, no user data) |
| Static properties / `static` locals | ✅ | None; only static closures in `Configuration` OptionsResolver validators |
| `ResetInterface` / `kernel.reset` coverage | ✅ | `IconSupportWarningSubscriber` implements `ResetInterface` (auto-tagged); under Scenario B the flag simply stays `true` (once per worker) |
| Request / user / locale captured in services | ✅ | Form options and values live on per-request `FormView` / `FormInterface` only |
| Superglobals, `$_ENV`, `putenv`, `ini_set`, `setlocale`, timezone | ✅ | None used; config is compiled into container parameters |
| Doctrine / EntityManager | ✅ N/A | No persistence |
| Output, headers, `exit`, shutdown functions | ✅ | None |
| Resources (files, sockets, cURL) held open | ✅ | None; HTTP client is only checked with `interface_exists()`, never called |
| Memory growth across requests | ✅ | No caches or accumulating arrays |
| Blocking I/O and timeouts | ✅ N/A | No I/O in the bundle (icon fetching is UX Icons’ responsibility) |
| Third-party static state | ✅ | Symfony Form / DI / Config / EventDispatcher; Monolog channel logger injected |
| PHPStan FrankenPHP rulesets | ✅ | `ruleset-classic.neon` + `ruleset-worker-strict.neon` + `ruleset-hardening.neon` in `phpstan.neon.dist` |
| Browser JS | ✅ | `nowo-password-toggle.js` runs in the browser (no PHP worker memory); custom element + event delegation only |

Worker demos: `demo/symfony8` and `demo/symfony8-php85` default to `FRANKENPHP_MODE=worker` and document `FRANKENPHP_RESET_KERNEL=false`.

## Services reviewed

| Service | Shared | Mutable state | Scenario A | Scenario B |
|---------|--------|---------------|------------|------------|
| `Nowo\PasswordToggleBundle\Form\Type\PasswordType` (`form.type`) | yes | none (`readonly array $defaults`; `readonly` checker) | ✅ | ✅ |
| `Nowo\PasswordToggleBundle\IconSupport\IconSupportChecker` | yes | none (`final readonly`; `class_exists` / `interface_exists`) | ✅ | ✅ |
| `Nowo\PasswordToggleBundle\EventSubscriber\IconSupportWarningSubscriber` | yes | `bool $warned` (log de-duplication only) | ✅ (reset each request → warn again if packages missing) | ✅ (stays `true`, warn once per worker) |

`NowoPasswordToggleExtension`, `Configuration`, and `TwigPathsPass` only run at container compile time. The Twig widget writes only into the current request’s HTML and request attributes (`_nowo_password_toggle_js` for single script include). Frontend JS does not share PHP worker memory.

## Findings

No open findings that block Scenario B.

### Info — W-01 — One-time icon warning under Scenario A (Low / informational)

- **Where:** `IconSupportWarningSubscriber::$warned` + `reset()`.
- **Impact:** Under Scenario A, `services_resetter` clears the guard after each request, so a missing `symfony/ux-icons` / `symfony/http-client` produces one warning **per main request** (same as PHP-FPM). Under Scenario B the warning is once per worker. No user or request data is stored.
- **Action:** install the icon packages or lower the `nowo_password_toggle` Monolog channel in production. No code change required for sticky-Kernel compatibility.

Covered by unit regression `PasswordTypeTest::testSharedInstanceDoesNotLeakOptionsAcrossConsecutiveBuilds`.

## Usage recommendations in worker mode

- No special configuration or reset hook is needed for this bundle when `FRANKENPHP_RESET_KERNEL=false`.
- Applications that decorate `PasswordType` must not store submitted values in service properties (or must implement `ResetInterface`) to keep this verdict. `always_empty` defaults to `true`.
- Prefer `asset('js/nowo-password-toggle.js', 'nowo_password_toggle')` after `assets:install` (already used by the default widget when `symfony/asset` is present).

## Re-audit triggers

Re-run this audit when a change adds: mutable properties to `PasswordType` or `IconSupportChecker`, a new event listener/subscriber, a PHP-side cache, an HTTP call from the bundle, or any use of `$_SERVER` / `$_ENV` / session at runtime.
