<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Tests;

use Nowo\PasswordToggleBundle\DependencyInjection\Compiler\TwigPathsPass;
use Nowo\PasswordToggleBundle\DependencyInjection\NowoPasswordToggleExtension;
use Nowo\PasswordToggleBundle\NowoPasswordToggleBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Tests for NowoPasswordToggleBundle.
 *
 * @author Héctor Franco Aceituno <hectorfranco@nowo.com>
 * @copyright 2026 Nowo.tech
 */
final class NowoPasswordToggleBundleTest extends TestCase
{
    public function testBuildRegistersTwigPathsCompilerPass(): void
    {
        $bundle    = new NowoPasswordToggleBundle();
        $container = new ContainerBuilder();

        $bundle->build($container);

        $hasTwigPathsPass = false;
        foreach ($container->getCompilerPassConfig()->getPasses() as $pass) {
            if ($pass instanceof TwigPathsPass) {
                $hasTwigPathsPass = true;
                break;
            }
        }

        $this->assertTrue($hasTwigPathsPass);
    }

    public function testGetContainerExtensionReturnsInstance(): void
    {
        $bundle    = new NowoPasswordToggleBundle();
        $extension = $bundle->getContainerExtension();

        $this->assertInstanceOf(NowoPasswordToggleExtension::class, $extension);
    }

    public function testGetContainerExtensionReturnsSameInstance(): void
    {
        $bundle     = new NowoPasswordToggleBundle();
        $extension1 = $bundle->getContainerExtension();
        $extension2 = $bundle->getContainerExtension();

        $this->assertSame($extension1, $extension2);
    }

    public function testGetContainerExtensionAlias(): void
    {
        $bundle    = new NowoPasswordToggleBundle();
        $extension = $bundle->getContainerExtension();

        $this->assertSame('nowo_password_toggle', $extension->getAlias());
    }

    public function testGetContainerExtensionInitializesOnlyOnce(): void
    {
        $bundle = new NowoPasswordToggleBundle();

        // First call should create the extension
        $extension1 = $bundle->getContainerExtension();

        // Second call should return the same instance (already initialized)
        $extension2 = $bundle->getContainerExtension();
        $this->assertSame($extension1, $extension2);
    }
}
