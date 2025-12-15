<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration definition for Password Toggle Bundle.
 *
 * This class defines the structure and default values for the bundle configuration.
 * Users can override these defaults in their config/packages/nowo_password_toggle.yaml file.
 *
 * @author Héctor Franco Aceituno <hectorfranco@nowo.tech>
 * @copyright 2024 Nowo.tech
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Builds the configuration tree.
     *
     * Defines the structure of the bundle configuration with all available options
     * and their default values. These defaults will be used unless overridden
     * when using the PasswordType form type.
     *
     * @return TreeBuilder The configuration tree builder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('nowo_password_toggle');

        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('toggle')
                    ->defaultTrue()
                    ->info('Enable/disable toggle functionality by default')
                ->end()
                ->scalarNode('visible_icon')
                    ->defaultValue('tabler:eye-off')
                    ->info('Icon when password is hidden (default)')
                    ->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(fn ($v) => !is_string($v) || trim($v) === '')
                        ->thenInvalid('visible_icon must be a non-empty string')
                    ->end()
                ->end()
                ->scalarNode('hidden_icon')
                    ->defaultValue('tabler:eye')
                    ->info('Icon when password is visible (default)')
                    ->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(fn ($v) => !is_string($v) || trim($v) === '')
                        ->thenInvalid('hidden_icon must be a non-empty string')
                    ->end()
                ->end()
                ->scalarNode('visible_label')
                    ->defaultValue('Show')
                    ->info('Label when password is hidden (default)')
                    ->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(fn ($v) => !is_string($v) || trim($v) === '')
                        ->thenInvalid('visible_label must be a non-empty string')
                    ->end()
                ->end()
                ->scalarNode('hidden_label')
                    ->defaultValue('Hide')
                    ->info('Label when password is visible (default)')
                    ->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(fn ($v) => !is_string($v) || trim($v) === '')
                        ->thenInvalid('hidden_label must be a non-empty string')
                    ->end()
                ->end()
                ->arrayNode('button_classes')
                    ->defaultValue(['input-group-text', 'cursor-pointer'])
                    ->info('CSS classes for toggle button (default)')
                    ->scalarPrototype()->end()
                    ->validate()
                        ->ifTrue(fn ($v) => !is_array($v))
                        ->thenInvalid('button_classes must be an array')
                    ->end()
                ->end()
                ->arrayNode('toggle_container_classes')
                    ->defaultValue(['form-password-toggle'])
                    ->info('CSS classes for container (default)')
                    ->scalarPrototype()->end()
                    ->validate()
                        ->ifTrue(fn ($v) => !is_array($v))
                        ->thenInvalid('toggle_container_classes must be an array')
                    ->end()
                ->end()
                ->booleanNode('use_toggle_form_theme')
                    ->defaultTrue()
                    ->info('Use the bundle\'s form theme for rendering (default)')
                ->end()
                ->booleanNode('always_empty')
                    ->defaultTrue()
                    ->info('Always render empty value (default)')
                ->end()
                ->booleanNode('trim')
                    ->defaultFalse()
                    ->info('Trim whitespace (default)')
                ->end()
                ->scalarNode('invalid_message')
                    ->defaultValue('The password is invalid.')
                    ->info('Invalid message (default)')
                    ->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(fn ($v) => !is_string($v) || trim($v) === '')
                        ->thenInvalid('invalid_message must be a non-empty string')
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
