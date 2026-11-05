<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Form\Extension;

use Nowo\PasswordToggleBundle\Form\ToggleLabels;
use Nowo\PasswordToggleBundle\Form\Type\PasswordType as TogglePasswordType;
use Nowo\PasswordToggleBundle\IconSupport\IconSupportChecker;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

use function array_search;
use function array_splice;
use function in_array;
use function is_array;
use function is_int;
use function is_string;

/**
 * The toggle on Symfony's own {@see PasswordType} (2.7, opt-in `nowo_password_toggle.extend_core_password_type`):
 * every `PasswordType` of the application — including the ones other bundles build (`RepeatedType` children, secret
 * fields…) — renders through the `toggle_password_widget` block with the bundle defaults.
 *
 * In `finishView` the `toggle_password` block prefix is inserted right after `password` (so `_<field>_widget` blocks
 * of a host theme still win) and the widget variables are filled from `nowo_password_toggle`. Options added to
 * Symfony's type: `toggle` (default from the config; `false` opts a field out), `visible_label` / `hidden_label`
 * (default from the config, translated in `translation_domain` when set). Symfony's `always_empty` keeps working: the
 * value is never rendered back by default.
 *
 * Stateless (worker-safe): only readonly configuration.
 *
 * @author Héctor Franco Aceituno <hectorfranco@nowo.tech>
 * @copyright 2026 Nowo.tech
 */
final class CorePasswordTypeExtension extends AbstractTypeExtension
{
    public const BLOCK_PREFIX = 'toggle_password';

    /** Widget variables copied from the configuration, with the bundle type's own fallbacks. */
    private const WIDGET_DEFAULTS = [
        'toggle_container_classes' => ['form-password-toggle'],
        'button_classes'           => ['input-group-text', 'cursor-pointer'],
        'visible_icon'             => 'tabler:eye-off',
        'hidden_icon'              => 'tabler:eye',
        'javascript'               => TogglePasswordType::JAVASCRIPT_WEB_COMPONENT,
        'stimulus_controller'      => 'nowo-password-toggle',
        'csp_nonce_attribute'      => 'csp_nonce',
    ];

    private readonly IconSupportChecker $iconSupportChecker;

    /**
     * @param array<string, mixed> $defaults `nowo_password_toggle` configuration
     */
    public function __construct(
        private readonly array $defaults = [],
        ?IconSupportChecker $iconSupportChecker = null,
        private readonly ?TranslatorInterface $translator = null,
    ) {
        $this->iconSupportChecker = $iconSupportChecker ?? new IconSupportChecker();
    }

    public static function getExtendedTypes(): iterable
    {
        return [PasswordType::class];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'toggle'        => (bool) ($this->defaults['toggle'] ?? true),
            'visible_label' => is_string($this->defaults['visible_label'] ?? null) ? $this->defaults['visible_label'] : 'Show',
            'hidden_label'  => is_string($this->defaults['hidden_label'] ?? null) ? $this->defaults['hidden_label'] : 'Hide',
        ]);
        $resolver->setAllowedTypes('toggle', 'bool');
        $resolver->setAllowedTypes('visible_label', 'string');
        $resolver->setAllowedTypes('hidden_label', 'string');
    }

    /**
     * @param FormInterface<mixed> $form
     * @param array<string, mixed> $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        if ($options['toggle'] !== true) {
            return;
        }
        $prefixes = is_array($view->vars['block_prefixes'] ?? null) ? $view->vars['block_prefixes'] : [];
        if (in_array(self::BLOCK_PREFIX, $prefixes, true)) {
            return;
        }
        $at = array_search('password', $prefixes, true);
        if (!is_int($at)) {
            return;
        }
        array_splice($prefixes, $at + 1, 0, [self::BLOCK_PREFIX]);
        $view->vars['block_prefixes'] = $prefixes;

        $domain                        = is_string($this->defaults['translation_domain'] ?? null) ? $this->defaults['translation_domain'] : null;
        $view->vars['toggle']          = true;
        $view->vars['icons_available'] = $this->iconSupportChecker->isIconRenderingSupported();
        $view->vars['visible_label']   = ToggleLabels::label((string) $options['visible_label'], $domain, $this->translator);
        $view->vars['hidden_label']    = ToggleLabels::label((string) $options['hidden_label'], $domain, $this->translator);
        foreach (self::WIDGET_DEFAULTS as $name => $fallback) {
            $view->vars[$name] = $this->defaults[$name] ?? $fallback;
        }
    }
}
