<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\DependencyInjection;

use Nowo\PasswordToggleBundle\EventSubscriber\IconSupportWarningSubscriber;
use Nowo\PasswordToggleBundle\IconSupport\IconSupportChecker;
use Symfony\Component\Asset\Package;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Dependency injection extension for the PasswordToggle bundle.
 *
 * This extension loads and manages the bundle's service definitions and configuration.
 * It is responsible for registering the form type service and any other bundle services.
 *
 * @author Héctor Franco Aceituno <hectorfranco@nowo.tech>
 * @copyright 2026 Nowo.tech
 */
final class NowoPasswordToggleExtension extends Extension implements PrependExtensionInterface
{
    /**
     * Loads the services configuration and processes the bundle configuration.
     *
     * This method loads the services.yaml file from the bundle's Resources/config directory
     * and registers all bundle services with the dependency injection container.
     * It also processes the bundle configuration and stores it as container parameters.
     *
     * @param array<int, array<string, mixed>> $configs Array of configuration arrays from config files
     * @param ContainerBuilder $container The container builder instance
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $checker = new IconSupportChecker();

        // Store the processed configuration as container parameters
        $container->setParameter('nowo_password_toggle.defaults', $config);
        $container->setParameter('nowo_password_toggle.icon_support.available', $checker->isIconRenderingSupported());

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        if ($container->hasDefinition(IconSupportWarningSubscriber::class) && $container->hasExtension('monolog')) {
            $container->getDefinition(IconSupportWarningSubscriber::class)
                ->setArgument('$logger', new Reference('monolog.logger.nowo_password_toggle'));
        }
    }

    public function prepend(ContainerBuilder $container): void
    {
        // Only when symfony/asset is installed — FrameworkBundle rejects assets config otherwise.
        if ($container->hasExtension('framework') && class_exists(Package::class)) {
            $container->prependExtensionConfig('framework', [
                'assets' => [
                    'packages' => [
                        'nowo_password_toggle' => [
                            'base_path' => '/bundles/nowopasswordtoggle',
                        ],
                    ],
                ],
            ]);
        }

        if (!$container->hasExtension('monolog')) {
            return;
        }

        $container->prependExtensionConfig('monolog', [
            'channels' => ['nowo_password_toggle'],
        ]);
    }

    /**
     * Returns the alias name of the extension.
     *
     * This alias is used in Symfony configuration files to reference this extension.
     * For example: `nowo_password_toggle:` in config files.
     *
     * @return string The alias name of the extension
     */
    public function getAlias(): string
    {
        return 'nowo_password_toggle';
    }
}
