<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use function dirname;

/**
 * Registers the bundle's Twig views path at the end of the native loader so that
 * application overrides (templates/bundles/NowoPasswordToggleBundle/) are consulted first.
 */
final class TwigPathsPass implements CompilerPassInterface
{
    private const TWIG_NAMESPACE = 'NowoPasswordToggleBundle';

    public function process(ContainerBuilder $container): void
    {
        $loaderId = $this->getNativeLoaderServiceId($container);
        if ($loaderId === null) {
            return;
        }

        $viewsPath = dirname(__DIR__, 2) . '/Resources/views';

        $container->getDefinition($loaderId)
            ->addMethodCall('addPath', [$viewsPath, self::TWIG_NAMESPACE]);
    }

    private function getNativeLoaderServiceId(ContainerBuilder $container): ?string
    {
        if ($container->hasAlias('twig.loader.native')) {
            $alias = $container->getAlias('twig.loader.native');

            return (string) $alias;
        }
        if ($container->hasDefinition('twig.loader.native')) {
            return 'twig.loader.native';
        }
        if ($container->hasDefinition('twig.loader.native_filesystem')) {
            return 'twig.loader.native_filesystem';
        }

        return null;
    }
}
