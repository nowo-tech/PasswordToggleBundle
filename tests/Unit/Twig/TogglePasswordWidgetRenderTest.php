<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Tests\Unit\Twig;

use Nowo\PasswordToggleBundle\Form\Type\PasswordType;
use Nowo\PasswordToggleBundle\IconSupport\IconSupportChecker;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Twig\TwigFunction;

use function dirname;

/**
 * Renders the real widget through the Symfony form Twig bridge (strict variables) to check CSP output.
 */
final class TogglePasswordWidgetRenderTest extends TestCase
{
    public function testWebComponentModeEmitsNoncedScriptOncePerRequestAndNoInlineHandlers(): void
    {
        $request = new Request();
        $request->attributes->set('csp_nonce', 'r4nd0m"n');

        $html = $this->render([], $request, 2);

        self::assertStringContainsString('<nowo-password-toggle', $html);
        self::assertSame(1, substr_count($html, '<script '), 'script emitted once per request');
        self::assertStringContainsString('<script src="/bundles/nowopasswordtoggle/js/nowo-password-toggle.js" nonce="r4nd0m&quot;n" defer></script>', $html);
        self::assertDoesNotMatchRegularExpression('/\son[a-z]+=/i', $html);
        self::assertDoesNotMatchRegularExpression('/\sstyle=/i', $html);
        self::assertStringNotContainsString('data-controller', $html);
    }

    public function testNoNonceAttributeWithoutRequestNonce(): void
    {
        $html = $this->render([], new Request());

        self::assertStringContainsString('<script src="/bundles/nowopasswordtoggle/js/nowo-password-toggle.js" defer></script>', $html);
        self::assertStringNotContainsString('nonce=', $html);
    }

    public function testCustomNonceAttributeAndDisabledNonce(): void
    {
        $request = new Request();
        $request->attributes->set('_my_nonce', 'abc123');
        $request->attributes->set('csp_nonce', 'ignored');

        self::assertStringContainsString('nonce="abc123"', $this->render(['csp_nonce_attribute' => '_my_nonce'], $request));
        self::assertStringNotContainsString('nonce=', $this->render(['csp_nonce_attribute' => ''], $request));
        self::assertStringNotContainsString('nonce=', $this->render(['csp_nonce_attribute' => null], $request));
    }

    public function testRendersWithoutRequest(): void
    {
        $html = $this->render([], null);

        self::assertStringContainsString('<script src="/bundles/nowopasswordtoggle/js/nowo-password-toggle.js" defer></script>', $html);
    }

    public function testStimulusModeRendersControllerMarkupAndNoScript(): void
    {
        $request = new Request();
        $request->attributes->set('csp_nonce', 'abc');

        $html = $this->render(['javascript' => 'stimulus', 'visible_label' => 'Show', 'hidden_label' => 'Hide'], $request);

        self::assertStringNotContainsString('<script', $html);
        self::assertStringContainsString('data-controller="nowo-password-toggle"', $html);
        self::assertStringContainsString('data-nowo-password-toggle-init="1"', $html);
        self::assertStringContainsString('data-nowo-password-toggle-visible-label-value="Show"', $html);
        self::assertStringContainsString('data-nowo-password-toggle-hidden-label-value="Hide"', $html);
        self::assertStringContainsString('data-action="click->nowo-password-toggle#toggle keydown->nowo-password-toggle#keydown"', $html);
        self::assertDoesNotMatchRegularExpression('/\son[a-z]+=/i', $html);
        self::assertSame(1, substr_count($html, 'data-nowo-password-toggle-target="button"'), 'no duplicate target attribute for the default identifier');

        $custom = $this->render(['javascript' => 'stimulus', 'stimulus_controller' => 'password-toggle'], $request);
        self::assertStringContainsString('data-controller="password-toggle"', $custom);
        self::assertStringContainsString('data-password-toggle-target="button"', $custom);
    }

    public function testNoneModeRendersMarkupOnly(): void
    {
        $html = $this->render(['javascript' => 'none'], new Request());

        self::assertStringContainsString('<nowo-password-toggle', $html);
        self::assertStringNotContainsString('<script', $html);
        self::assertStringNotContainsString('data-controller', $html);
    }

    /**
     * @param array<string, mixed> $options
     */
    private function render(array $options, ?Request $request, int $fields = 1): string
    {
        $bridgeViews = dirname((string) (new ReflectionClass(AppVariable::class))->getFileName()) . '/Resources/views/Form';
        $bundleViews = dirname(__DIR__, 3) . '/src/Resources/views/Form';

        $templates = '';
        for ($i = 0; $i < $fields; ++$i) {
            $templates .= '{{ form_widget(forms[' . $i . ']) }}';
        }

        $twig = new Environment(new ChainLoader([
            new ArrayLoader(['page.html.twig' => $templates]),
            new FilesystemLoader([$bridgeViews, $bundleViews]),
        ]), ['strict_variables' => true, 'autoescape' => 'html']);
        $twig->addExtension(new FormExtension());
        $twig->addExtension(new TranslationExtension());
        $twig->addFunction(new TwigFunction('asset', static fn (string $path, ?string $package = null): string => '/bundles/nowopasswordtoggle/' . $path));
        $twig->addFunction(new TwigFunction('ux_icon', static fn (): string => '<svg></svg>', ['is_safe' => ['html']]));

        $engine = new TwigRendererEngine(['form_div_layout.html.twig', 'toggle_password_widget.html.twig'], $twig);
        $twig->addRuntimeLoader(new FactoryRuntimeLoader([
            FormRenderer::class => static fn (): FormRenderer => new FormRenderer($engine),
        ]));

        $app = new AppVariable();
        $app->setEnvironment('prod');
        if ($request instanceof Request) {
            $stack = new RequestStack();
            $stack->push($request);
            $app->setRequestStack($stack);
        } else {
            $app->setRequestStack(new RequestStack());
        }
        $twig->addGlobal('app', $app);

        $factory = Forms::createFormFactoryBuilder()
            ->addType(new PasswordType([], new IconSupportChecker(uxIconsAvailable: false, httpClientAvailable: false)))
            ->getFormFactory();

        $forms = [];
        for ($i = 0; $i < $fields; ++$i) {
            $forms[] = $factory->createNamed('password' . $i, PasswordType::class, null, $options)->createView();
        }

        return $twig->render('page.html.twig', ['forms' => $forms]);
    }
}
