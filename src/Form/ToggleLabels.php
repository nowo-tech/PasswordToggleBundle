<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Form;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Show / hide labels of the toggle (2.7): printed as is, or, with `nowo_password_toggle.translation_domain` set,
 * translated as keys of that domain (the bundle ships `NowoPasswordToggleBundle` with `show` / `hide` and the default
 * labels `Show` / `Hide` in 7 locales).
 *
 * @author Héctor Franco Aceituno <hectorfranco@nowo.tech>
 * @copyright 2026 Nowo.tech
 */
final class ToggleLabels
{
    /** Translation domain shipped by the bundle. */
    public const DOMAIN = 'NowoPasswordToggleBundle';

    /**
     * The label to print: translated in `$domain` when both a translator and a domain are given, else as is.
     */
    public static function label(string $label, ?string $domain, ?TranslatorInterface $translator): string
    {
        if (!$translator instanceof TranslatorInterface || $domain === null || $domain === '' || $label === '') {
            return $label;
        }

        return $translator->trans($label, [], $domain);
    }
}
