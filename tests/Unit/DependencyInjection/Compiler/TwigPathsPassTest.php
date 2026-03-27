<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Tests\DependencyInjection\Compiler;

use Nowo\PasswordToggleBundle\DependencyInjection\Compiler\TwigPathsPass;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Tests for TwigPathsPass compiler pass.
 *
 * @author Héctor Franco Aceituno <hectorfranco@nowo.com>
 * @copyright 2026 Nowo.tech
 */
final class TwigPathsPassTest extends TestCase
{
    public function testProcessSkipsWhenNativeLoaderIsMissing(): void
    {
        $container = new ContainerBuilder();
        $pass      = new TwigPathsPass();

        $pass->process($container);

        $this->assertFalse($container->hasDefinition('twig.loader.native'));
        $this->assertFalse($container->hasDefinition('twig.loader.native_filesystem'));
    }

    public function testProcessAddsPathToNativeFilesystemLoaderDefinition(): void
    {
        $container = new ContainerBuilder();
        $container->setDefinition('twig.loader.native_filesystem', new Definition(stdClass::class));
        $pass = new TwigPathsPass();

        $pass->process($container);

        $methodCalls = $container->getDefinition('twig.loader.native_filesystem')->getMethodCalls();
        $this->assertCount(1, $methodCalls);
        $this->assertSame('addPath', $methodCalls[0][0]);
        $this->assertIsArray($methodCalls[0][1]);
        $this->assertCount(2, $methodCalls[0][1]);
        $this->assertStringEndsWith('/src/Resources/views', $methodCalls[0][1][0]);
        $this->assertSame('NowoPasswordToggleBundle', $methodCalls[0][1][1]);
    }

    public function testProcessUsesNativeLoaderAliasWhenPresent(): void
    {
        $container = new ContainerBuilder();
        $container->setDefinition('custom.loader', new Definition(stdClass::class));
        $container->setAlias('twig.loader.native', 'custom.loader');
        $pass = new TwigPathsPass();

        $pass->process($container);

        $methodCalls = $container->getDefinition('custom.loader')->getMethodCalls();
        $this->assertCount(1, $methodCalls);
        $this->assertSame('addPath', $methodCalls[0][0]);
        $this->assertSame('NowoPasswordToggleBundle', $methodCalls[0][1][1]);
    }

    public function testProcessUsesNativeLoaderDefinitionWhenPresent(): void
    {
        $container = new ContainerBuilder();
        $container->setDefinition('twig.loader.native', new Definition(stdClass::class));
        $pass = new TwigPathsPass();

        $pass->process($container);

        $methodCalls = $container->getDefinition('twig.loader.native')->getMethodCalls();
        $this->assertCount(1, $methodCalls);
        $this->assertSame('addPath', $methodCalls[0][0]);
        $this->assertSame('NowoPasswordToggleBundle', $methodCalls[0][1][1]);
    }
}
