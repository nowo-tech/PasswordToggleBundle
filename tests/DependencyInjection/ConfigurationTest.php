<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Tests\DependencyInjection;

use Nowo\PasswordToggleBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * Tests for Configuration class.
 *
 * @author Héctor Franco Aceituno <hectorfranco@nowo.tech>
 * @copyright 2024 Nowo.tech
 */
final class ConfigurationTest extends TestCase
{
    private Configuration $configuration;
    private Processor $processor;

    protected function setUp(): void
    {
        $this->configuration = new Configuration();
        $this->processor = new Processor();
    }

    public function testGetConfigTreeBuilder(): void
    {
        $treeBuilder = $this->configuration->getConfigTreeBuilder();
        $this->assertNotNull($treeBuilder);
    }

    public function testDefaultConfiguration(): void
    {
        $config = $this->processor->processConfiguration($this->configuration, []);

        $this->assertTrue($config['toggle']);
        $this->assertSame('tabler:eye-off', $config['visible_icon']);
        $this->assertSame('tabler:eye', $config['hidden_icon']);
        $this->assertSame('Show', $config['visible_label']);
        $this->assertSame('Hide', $config['hidden_label']);
        $this->assertSame(['input-group-text', 'cursor-pointer'], $config['button_classes']);
        $this->assertSame(['form-password-toggle'], $config['toggle_container_classes']);
        $this->assertTrue($config['use_toggle_form_theme']);
        $this->assertTrue($config['always_empty']);
        $this->assertFalse($config['trim']);
        $this->assertSame('The password is invalid.', $config['invalid_message']);
    }

    public function testCustomConfiguration(): void
    {
        $configs = [
            [
                'toggle' => false,
                'visible_icon' => 'custom:eye-off',
                'hidden_icon' => 'custom:eye',
                'visible_label' => 'Mostrar',
                'hidden_label' => 'Ocultar',
                'button_classes' => ['btn', 'btn-primary'],
                'toggle_container_classes' => ['custom-container'],
            ],
        ];

        $config = $this->processor->processConfiguration($this->configuration, $configs);

        $this->assertFalse($config['toggle']);
        $this->assertSame('custom:eye-off', $config['visible_icon']);
        $this->assertSame('custom:eye', $config['hidden_icon']);
        $this->assertSame('Mostrar', $config['visible_label']);
        $this->assertSame('Ocultar', $config['hidden_label']);
        $this->assertSame(['btn', 'btn-primary'], $config['button_classes']);
        $this->assertSame(['custom-container'], $config['toggle_container_classes']);
    }

    public function testConfigurationValidationRejectsEmptyIcon(): void
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\InvalidConfigurationException::class);

        $configs = [
            [
                'visible_icon' => '',
            ],
        ];

        $this->processor->processConfiguration($this->configuration, $configs);
    }

    public function testConfigurationValidationRejectsEmptyLabel(): void
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\InvalidConfigurationException::class);

        $configs = [
            [
                'visible_label' => '',
            ],
        ];

        $this->processor->processConfiguration($this->configuration, $configs);
    }

    public function testConfigurationValidationRejectsNonArrayButtonClasses(): void
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\InvalidTypeException::class);

        $configs = [
            [
                'button_classes' => 'not-an-array',
            ],
        ];

        $this->processor->processConfiguration($this->configuration, $configs);
    }
}

