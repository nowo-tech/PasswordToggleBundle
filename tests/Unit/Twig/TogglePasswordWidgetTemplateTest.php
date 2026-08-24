<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Tests\Unit\Twig;

use PHPUnit\Framework\TestCase;

use function dirname;

final class TogglePasswordWidgetTemplateTest extends TestCase
{
    public function testTemplateContainsGracefulIconFallback(): void
    {
        $path    = dirname(__DIR__, 3) . '/src/Resources/views/Form/toggle_password_widget.html.twig';
        $content = file_get_contents($path);

        $this->assertIsString($content);
        $this->assertStringContainsString('icons_available', $content);
        $this->assertStringNotContainsString('ux_icon is defined', $content);
        $this->assertStringNotContainsString('d-none', $content);
        $this->assertStringNotContainsString('style.display', $content);
        $this->assertStringNotContainsString("style: 'display: none'", $content);
        $this->assertStringContainsString('is-password-visible', $content);
        $this->assertStringContainsString('nowo-password-toggle', $content);
        $this->assertStringContainsString("asset('js/nowo-password-toggle.js', 'nowo_password_toggle')", $content);
        $this->assertStringNotContainsString('onclick=', $content);
        $this->assertStringNotContainsString('onkeydown=', $content);
        $this->assertStringContainsString('password-toggle-icon-missing', $content);
    }

    public function testToggleScriptAssetDefinesCustomElement(): void
    {
        $path    = dirname(__DIR__, 3) . '/src/Resources/public/js/nowo-password-toggle.js';
        $content = file_get_contents($path);

        $this->assertIsString($content);
        $this->assertStringContainsString('customElements.define(TAG, NowoPasswordToggleElement)', $content);
        $this->assertStringContainsString('nowo-password-toggle', $content);
        $this->assertStringContainsString('NowoPasswordToggle', $content);
        $this->assertStringNotContainsString('onclick=', $content);
    }
}
