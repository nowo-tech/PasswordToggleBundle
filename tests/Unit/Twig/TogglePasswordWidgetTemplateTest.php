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
        $this->assertStringContainsString('ux_icon is defined', $content);
        $this->assertStringContainsString('password-toggle-icon-missing', $content);
    }
}
