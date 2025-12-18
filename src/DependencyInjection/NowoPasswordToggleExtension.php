<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Dependency injection extension for the PasswordToggle bundle.
 *
 * This extension loads and manages the bundle's service definitions and configuration.
 * It is responsible for registering the form type service and any other bundle services.
 *
 * @author Héctor Franco Aceituno <hectorfranco@nowo.tech>
 * @copyright 2025 Nowo.tech
 */
class NowoPasswordToggleExtension extends Extension
{
    /**
     * Loads the services configuration and processes the bundle configuration.
     *
     * This method loads the services.yaml file from the bundle's Resources/config directory
     * and registers all bundle services with the dependency injection container.
     * It also processes the bundle configuration and stores it as container parameters.
     *
     * @param array<string, mixed> $configs   Array of configuration values from config files
     * @param ContainerBuilder     $container The container builder instance
     *
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Store the processed configuration as container parameters
        $container->setParameter('nowo_password_toggle.defaults', $config);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
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
