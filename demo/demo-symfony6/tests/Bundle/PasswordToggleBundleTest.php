<?php

declare(strict_types=1);

namespace App\Tests\Bundle;

use Nowo\PasswordToggleBundle\NowoPasswordToggleBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Tests for Password Toggle Bundle integration.
 *
 * Verifies that the bundle is correctly registered and available.
 *
 * @covers \Nowo\PasswordToggleBundle\NowoPasswordToggleBundle
 */
final class PasswordToggleBundleTest extends TestCase
{
    /**
     * Tests that the bundle extends Symfony Bundle class.
     */
    public function testBundleExtendsSymfonyBundle(): void
    {
        $bundle = new NowoPasswordToggleBundle();

        $this->assertInstanceOf(Bundle::class, $bundle);
    }

    /**
     * Tests that the bundle has a container extension.
     */
    public function testBundleHasContainerExtension(): void
    {
        $bundle = new NowoPasswordToggleBundle();
        $extension = $bundle->getContainerExtension();

        $this->assertNotNull($extension);
        $this->assertSame('nowo_password_toggle', $extension->getAlias());
    }
}

