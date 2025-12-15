<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Tests\DependencyInjection;

use Nowo\PasswordToggleBundle\DependencyInjection\NowoPasswordToggleExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Tests for NowoPasswordToggleExtension.
 *
 * @author Héctor Franco Aceituno <hectorfranco@nowo.com>
 * @copyright 2024 Nowo.tech
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
        $this->assertTrue($container->hasDefinition('Nowo\\PasswordToggleBundle\\Form\\Type\\PasswordType'));
    }

    public function testLoadWithConfig(): void
    {
        $container = new ContainerBuilder();
        $configs = [
          [
            'toggle' => false,
            'visible_icon' => 'custom:icon',
          ],
        ];

        // Should not throw any exception even with config
        $this->extension->load($configs, $container);

        // Verify that the PasswordType service is registered
        $this->assertTrue($container->hasDefinition('Nowo\\PasswordToggleBundle\\Form\\Type\\PasswordType'));

        // Verify that configuration is stored as parameter
        $this->assertTrue($container->hasParameter('nowo_password_toggle.defaults'));
        $defaults = $container->getParameter('nowo_password_toggle.defaults');
        $this->assertIsArray($defaults);
        $this->assertFalse($defaults['toggle']);
        $this->assertSame('custom:icon', $defaults['visible_icon']);
    }

    public function testLoadStoresConfigurationAsParameter(): void
    {
        $container = new ContainerBuilder();
        $configs = [
          [
            'toggle' => true,
            'visible_label' => 'Mostrar',
            'hidden_label' => 'Ocultar',
          ],
        ];

        $this->extension->load($configs, $container);

        $this->assertTrue($container->hasParameter('nowo_password_toggle.defaults'));
        $defaults = $container->getParameter('nowo_password_toggle.defaults');

        $this->assertTrue($defaults['toggle']);
        $this->assertSame('Mostrar', $defaults['visible_label']);
        $this->assertSame('Ocultar', $defaults['hidden_label']);
    }
}
