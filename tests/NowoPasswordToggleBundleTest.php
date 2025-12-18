<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Tests;

use Nowo\PasswordToggleBundle\DependencyInjection\NowoPasswordToggleExtension;
use Nowo\PasswordToggleBundle\NowoPasswordToggleBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * Tests for NowoPasswordToggleBundle.
 *
 * @author Héctor Franco Aceituno <hectorfranco@nowo.com>
 * @copyright 2025 Nowo.tech
 */
final class NowoPasswordToggleBundleTest extends TestCase
{
    public function testGetContainerExtensionReturnsInstance(): void
    {
        $bundle = new NowoPasswordToggleBundle();
        $extension = $bundle->getContainerExtension();

        $this->assertInstanceOf(ExtensionInterface::class, $extension);
        $this->assertInstanceOf(NowoPasswordToggleExtension::class, $extension);
        $this->assertNotNull($extension);
    }

    public function testGetContainerExtensionReturnsSameInstance(): void
    {
        $bundle = new NowoPasswordToggleBundle();
        $extension1 = $bundle->getContainerExtension();
        $extension2 = $bundle->getContainerExtension();

        $this->assertSame($extension1, $extension2);
    }

    public function testGetContainerExtensionAlias(): void
    {
        $bundle = new NowoPasswordToggleBundle();
        $extension = $bundle->getContainerExtension();

        $this->assertInstanceOf(ExtensionInterface::class, $extension);
        $this->assertSame('nowo_password_toggle', $extension->getAlias());
    }

    public function testGetContainerExtensionInitializesOnlyOnce(): void
    {
        $bundle = new NowoPasswordToggleBundle();

        // First call should create the extension
        $extension1 = $bundle->getContainerExtension();
        $this->assertNotNull($extension1);

        // Second call should return the same instance (already initialized)
        $extension2 = $bundle->getContainerExtension();
        $this->assertSame($extension1, $extension2);
    }
}
