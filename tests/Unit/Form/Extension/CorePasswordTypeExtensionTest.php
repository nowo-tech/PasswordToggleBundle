<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Tests\Unit\Form\Extension;

use Nowo\PasswordToggleBundle\Form\Extension\CorePasswordTypeExtension;
use Nowo\PasswordToggleBundle\Form\ToggleLabels;
use Nowo\PasswordToggleBundle\Form\Type\PasswordType as TogglePasswordType;
use Nowo\PasswordToggleBundle\IconSupport\IconSupportChecker;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Twig\TwigFunction;

use function dirname;
use function substr_count;

/**
 * 2.7: with `extend_core_password_type`, Symfony's own PasswordType (alone and inside a RepeatedType) renders the
 * toggle; labels are translated in `translation_domain`.
 */
final class CorePasswordTypeExtensionTest extends TestCase
{
    public function testAPlainSymfonyPasswordTypeGetsTheToggle(): void
    {
        $html = $this->render($this->factory(['javascript' => 'none']), PasswordType::class);

        self::assertSame(1, substr_count($html, '<nowo-password-toggle'));
        self::assertStringContainsString('type="password"', $html);
        self::assertStringContainsString('data-nowo-password-toggle-visible-label="Show"', $html);
        self::assertStringContainsString('aria-label="Show"', $html);
    }

    public function testARepeatedTypeOfIttGetsTheToggleOnBothFields(): void
    {
        $html = $this->render($this->factory(['javascript' => 'none']), RepeatedType::class, ['type' => PasswordType::class]);

        self::assertSame(2, substr_count($html, '<nowo-password-toggle'));
        self::assertSame(2, substr_count($html, 'type="password"'));
    }

    public function testWithoutTheExtensionNothingChanges(): void
    {
        $html = $this->render(Forms::createFormFactoryBuilder()->getFormFactory(), PasswordType::class);

        self::assertStringNotContainsString('<nowo-password-toggle', $html);
        self::assertStringContainsString('type="password"', $html);
    }

    public function testAFieldOptsOutAndTheConfigDefaultApplies(): void
    {
        self::assertStringNotContainsString('<nowo-password-toggle', $this->render($this->factory([]), PasswordType::class, ['toggle' => false]));
        self::assertStringNotContainsString('<nowo-password-toggle', $this->render($this->factory(['toggle' => false]), PasswordType::class));
        self::assertStringContainsString('<nowo-password-toggle', $this->render($this->factory(['toggle' => false]), PasswordType::class, ['toggle' => true]));
    }

    public function testWidgetVariablesComeFromTheConfiguration(): void
    {
        $view = $this->factory([
            'button_classes'           => ['btn', 'btn-outline'],
            'toggle_container_classes' => ['my-wrap'],
            'javascript'               => 'stimulus',
            'stimulus_controller'      => 'pwd',
            'csp_nonce_attribute'      => '_nonce',
        ])->createNamed('secret', PasswordType::class)->createView();

        self::assertSame(['form', 'text', 'password', CorePasswordTypeExtension::BLOCK_PREFIX, '_secret'], $view->vars['block_prefixes']);
        self::assertSame(['btn', 'btn-outline'], $view->vars['button_classes']);
        self::assertSame(['my-wrap'], $view->vars['toggle_container_classes']);
        self::assertSame('stimulus', $view->vars['javascript']);
        self::assertSame('pwd', $view->vars['stimulus_controller']);
        self::assertSame('_nonce', $view->vars['csp_nonce_attribute']);
        self::assertSame('tabler:eye-off', $view->vars['visible_icon']);
        self::assertFalse($view->vars['icons_available']);

        // finishView is idempotent and leaves unknown prefix lists alone.
        $extension                     = new CorePasswordTypeExtension([], $this->noIcons());
        $form                          = Forms::createFormFactory()->create(FormType::class);
        $again                         = new FormView();
        $again->vars['block_prefixes'] = ['form', 'text', 'password', 'toggle_password', '_x'];
        $extension->finishView($again, $form, ['toggle' => true, 'visible_label' => 'Show', 'hidden_label' => 'Hide']);
        self::assertSame(['form', 'text', 'password', 'toggle_password', '_x'], $again->vars['block_prefixes']);
        $other                         = new FormView();
        $other->vars['block_prefixes'] = ['form', 'text'];
        $extension->finishView($other, $form, ['toggle' => true, 'visible_label' => 'Show', 'hidden_label' => 'Hide']);
        self::assertSame(['form', 'text'], $other->vars['block_prefixes']);
        self::assertArrayNotHasKey('toggle', $other->vars);
        self::assertSame([PasswordType::class], [...CorePasswordTypeExtension::getExtendedTypes()]);
    }

    public function testLabelsAreTranslatedInTheConfiguredDomain(): void
    {
        $translator = $this->translator();
        $html       = $this->render($this->factory(['javascript' => 'none', 'translation_domain' => ToggleLabels::DOMAIN], $translator), PasswordType::class);

        self::assertStringContainsString('data-nowo-password-toggle-visible-label="Mostrar"', $html);
        self::assertStringContainsString('data-nowo-password-toggle-hidden-label="Ocultar"', $html);

        $custom = $this->render($this->factory(['javascript' => 'none', 'translation_domain' => ToggleLabels::DOMAIN], $translator), PasswordType::class, ['visible_label' => 'show', 'hidden_label' => 'hide']);
        self::assertStringContainsString('aria-label="Mostrar contraseña"', $custom);

        // Without a domain (default) the labels are printed as is.
        self::assertStringContainsString('visible-label="Show"', $this->render($this->factory(['javascript' => 'none'], $translator), PasswordType::class));
    }

    public function testTheBundleTypeTranslatesItsLabelsToo(): void
    {
        $type = new TogglePasswordType(['translation_domain' => ToggleLabels::DOMAIN], $this->noIcons(), $this->translator());
        $view = Forms::createFormFactoryBuilder()->addType($type)->getFormFactory()->create(TogglePasswordType::class)->createView();

        self::assertSame('Mostrar', $view->vars['visible_label']);
        self::assertSame('Ocultar', $view->vars['hidden_label']);
        self::assertSame('Show', ToggleLabels::label('Show', null, $this->translator()));
        self::assertSame('Show', ToggleLabels::label('Show', ToggleLabels::DOMAIN, null));
        self::assertSame('', ToggleLabels::label('', ToggleLabels::DOMAIN, $this->translator()));
    }

    /**
     * @param array<string, mixed> $defaults
     */
    private function factory(array $defaults, ?TranslatorInterface $translator = null): FormFactoryInterface
    {
        return Forms::createFormFactoryBuilder()
            ->addTypeExtension(new CorePasswordTypeExtension($defaults, $this->noIcons(), $translator))
            ->getFormFactory();
    }

    private function noIcons(): IconSupportChecker
    {
        return new IconSupportChecker(uxIconsAvailable: false, httpClientAvailable: false);
    }

    private function translator(): TranslatorInterface
    {
        return new class implements TranslatorInterface {
            private const MESSAGES = ['Show' => 'Mostrar', 'Hide' => 'Ocultar', 'show' => 'Mostrar contraseña', 'hide' => 'Ocultar contraseña'];

            /** @param array<string, mixed> $parameters */
            public function trans(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
            {
                return $domain === ToggleLabels::DOMAIN ? (self::MESSAGES[$id] ?? $id) : $id;
            }

            public function getLocale(): string
            {
                return 'es';
            }
        };
    }

    /**
     * @param class-string<FormTypeInterface<mixed>> $type
     * @param array<string, mixed> $options
     */
    private function render(FormFactoryInterface $factory, string $type, array $options = []): string
    {
        $bridgeViews = dirname((string) (new ReflectionClass(AppVariable::class))->getFileName()) . '/Resources/views/Form';
        $bundleViews = dirname(__DIR__, 4) . '/src/Resources/views/Form';
        $twig        = new Environment(new ChainLoader([
            new ArrayLoader(['page.html.twig' => '{{ form_widget(form) }}']),
            new FilesystemLoader([$bridgeViews, $bundleViews]),
        ]), ['strict_variables' => true, 'autoescape' => 'html']);
        $twig->addExtension(new FormExtension());
        $twig->addExtension(new TranslationExtension());
        $twig->addFunction(new TwigFunction('asset', static fn (string $path, ?string $package = null): string => '/bundles/nowopasswordtoggle/' . $path));
        $twig->addFunction(new TwigFunction('ux_icon', static fn (): string => '<svg></svg>', ['is_safe' => ['html']]));
        $engine = new TwigRendererEngine(['form_div_layout.html.twig', 'toggle_password_widget.html.twig'], $twig);
        $twig->addRuntimeLoader(new FactoryRuntimeLoader([FormRenderer::class => static fn (): FormRenderer => new FormRenderer($engine)]));
        $app = new AppVariable();
        $app->setEnvironment('prod');
        $app->setRequestStack(new RequestStack());
        $twig->addGlobal('app', $app);

        return $twig->render('page.html.twig', ['form' => $factory->createNamed('secret', $type, null, $options)->createView()]);
    }
}
