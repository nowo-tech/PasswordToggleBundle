<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Tests\DependencyInjection;

use Nowo\PasswordToggleBundle\DependencyInjection\NowoPasswordToggleExtension;
use Nowo\PasswordToggleBundle\EventSubscriber\IconSupportWarningSubscriber;
use Nowo\PasswordToggleBundle\Form\Type\PasswordType;
use Nowo\PasswordToggleBundle\IconSupport\IconSupportChecker;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\FrameworkExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

final class MonologExtensionStub extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
    }

    public function getAlias(): string
    {
        return 'monolog';
    }
}

/**
 * Tests for NowoPasswordToggleExtension.
 *
 * @author Héctor Franco Aceituno <hectorfranco@nowo.com>
 * @copyright 2026 Nowo.tech
 */
final class NowoPasswordToggleExtensionTest extends TestCase
{
    private NowoPasswordToggleExtension $extension;

    protected function setUp(): void
    {
        $this->extension = new NowoPasswordToggleExtension();
    }

    public function testGetAlias(): void
    {
        $this->assertSame('nowo_password_toggle', $this->extension->getAlias());
    }

    public function testLoad(): void
    {
        $container = new ContainerBuilder();

        // Should not throw any exception
        $this->extension->load([], $container);

        // Verify that the PasswordType service is registered
        $this->assertTrue($container->hasDefinition(PasswordType::class));
        $this->assertTrue($container->hasDefinition(IconSupportChecker::class));
        $this->assertTrue($container->hasDefinition(IconSupportWarningSubscriber::class));
        $this->assertTrue($container->hasParameter('nowo_password_toggle.icon_support.available'));
    }

    public function testLoadWithConfig(): void
    {
        $container = new ContainerBuilder();
        /** @var list<array<string, mixed>> $configs */
        $configs = [
            [
                'toggle'       => false,
                'visible_icon' => 'custom:icon',
            ],
        ];

        // Should not throw any exception even with config
        $this->extension->load($configs, $container);

        // Verify that the PasswordType service is registered
        $this->assertTrue($container->hasDefinition(PasswordType::class));

        // Verify that configuration is stored as parameter
        $this->assertTrue($container->hasParameter('nowo_password_toggle.defaults'));
        $defaults = $container->getParameter('nowo_password_toggle.defaults');
        $this->assertIsArray($defaults);
        /* @var array{toggle: bool, visible_icon: string} $defaults */
        $this->assertFalse($defaults['toggle']);
        $this->assertSame('custom:icon', $defaults['visible_icon']);
    }

    public function testLoadStoresConfigurationAsParameter(): void
    {
        $container = new ContainerBuilder();
        /** @var list<array<string, mixed>> $configs */
        $configs = [
            [
                'toggle'        => true,
                'visible_label' => 'Mostrar',
                'hidden_label'  => 'Ocultar',
            ],
        ];

        $this->extension->load($configs, $container);

        $this->assertTrue($container->hasParameter('nowo_password_toggle.defaults'));
        $defaults = $container->getParameter('nowo_password_toggle.defaults');
        $this->assertIsArray($defaults);
        /* @var array{toggle: bool, visible_label: string, hidden_label: string} $defaults */

        $this->assertTrue($defaults['toggle']);
        $this->assertSame('Mostrar', $defaults['visible_label']);
        $this->assertSame('Ocultar', $defaults['hidden_label']);
    }

    public function testPrependRegistersMonologChannelWhenMonologPresent(): void
    {
        $container = new ContainerBuilder();
        $container->registerExtension(new MonologExtensionStub());

        $this->extension->prepend($container);

        $this->assertSame(
            [['channels' => ['nowo_password_toggle']]],
            $container->getExtensionConfig('monolog'),
        );
    }

    public function testPrependIsNoOpWithoutMonolog(): void
    {
        $container = new ContainerBuilder();

        $this->extension->prepend($container);

        $this->assertFalse($container->hasExtension('monolog'));
    }

    public function testPrependConfiguresAssets(): void
    {
        $container = new ContainerBuilder();
        $container->registerExtension(new FrameworkExtension());

        $this->extension->prepend($container);

        $this->assertSame(
            '/bundles/nowopasswordtoggle',
            $container->getExtensionConfig('framework')[0]['assets']['packages']['nowo_password_toggle']['base_path'],
        );
    }

    public function testLoadWiresMonologLoggerWhenMonologExtensionPresent(): void
    {
        $container = new ContainerBuilder();
        $container->registerExtension(new MonologExtensionStub());
        $container->register('monolog.logger.nowo_password_toggle', NullLogger::class);

        $this->extension->load([], $container);

        $definition = $container->getDefinition(IconSupportWarningSubscriber::class);
        $this->assertEquals(
            new Reference('monolog.logger.nowo_password_toggle'),
            $definition->getArgument('$logger'),
        );
    }
}
