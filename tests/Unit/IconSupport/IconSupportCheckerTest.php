<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Tests\Unit\IconSupport;

use Nowo\PasswordToggleBundle\IconSupport\IconSupportChecker;
use PHPUnit\Framework\TestCase;

final class IconSupportCheckerTest extends TestCase
{
    public function testAllPackagesAvailable(): void
    {
        $checker = new IconSupportChecker(uxIconsAvailable: true, httpClientAvailable: true);

        $this->assertTrue($checker->isUxIconsAvailable());
        $this->assertTrue($checker->isHttpClientAvailable());
        $this->assertTrue($checker->isIconRenderingSupported());
        $this->assertSame([], $checker->getMissingPackages());
        $this->assertNull($checker->getActionableWarningMessage());
    }

    public function testMissingUxIcons(): void
    {
        $checker = new IconSupportChecker(uxIconsAvailable: false, httpClientAvailable: true);

        $this->assertFalse($checker->isIconRenderingSupported());
        $this->assertSame(['symfony/ux-icons'], $checker->getMissingPackages());
        $this->assertStringContainsString('symfony/ux-icons', (string) $checker->getActionableWarningMessage());
        $this->assertStringContainsString('ux:icons:lock', (string) $checker->getActionableWarningMessage());
    }

    public function testMissingHttpClient(): void
    {
        $checker = new IconSupportChecker(uxIconsAvailable: true, httpClientAvailable: false);

        $this->assertFalse($checker->isIconRenderingSupported());
        $this->assertSame(['symfony/http-client'], $checker->getMissingPackages());
        $this->assertStringContainsString('symfony/http-client', (string) $checker->getActionableWarningMessage());
    }

    public function testMissingBothPackages(): void
    {
        $checker = new IconSupportChecker(uxIconsAvailable: false, httpClientAvailable: false);

        $this->assertSame(['symfony/ux-icons', 'symfony/http-client'], $checker->getMissingPackages());
        $this->assertStringContainsString('symfony/ux-icons symfony/http-client', (string) $checker->getActionableWarningMessage());
    }

    public function testDetectsInstalledPackagesFromEnvironment(): void
    {
        $hasUxIcons = class_exists(\Symfony\UX\Icons\Twig\UXIconRuntime::class)
            || class_exists('Symfony\UX\Icons\Twig\UXIconsRuntime')
            || interface_exists(\Symfony\UX\Icons\IconRendererInterface::class);

        if (!$hasUxIcons) {
            $this->markTestSkipped('symfony/ux-icons is not installed.');
        }

        $checker = new IconSupportChecker();

        $this->assertTrue($checker->isUxIconsAvailable());
        $this->assertTrue($checker->isHttpClientAvailable());
        $this->assertTrue($checker->isIconRenderingSupported());
    }
}
