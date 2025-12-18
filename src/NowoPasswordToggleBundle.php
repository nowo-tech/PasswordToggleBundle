<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle;

use Nowo\PasswordToggleBundle\DependencyInjection\NowoPasswordToggleExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Symfony bundle for password toggle functionality.
 *
 * This bundle provides a custom form type with toggle visibility feature for password fields.
 * It extends the standard TextType and adds a toggle button to show/hide the password value.
 *
 * Features:
 * - Toggle button to show/hide password
 * - Customizable icons and labels
 * - Native JavaScript implementation (no Stimulus)
 * - Compatible with Symfony Live Components
 * - Full accessibility support (ARIA labels)
 *
 * @author Héctor Franco Aceituno <hectorfranco@nowo.tech>
 * @copyright 2025 Nowo.tech
 */
class NowoPasswordToggleBundle extends Bundle
{
    /**
     * Overridden to allow for the custom extension alias.
     *
     * Creates and returns the container extension instance if not already created.
     * The extension is cached after the first call to ensure the same instance is returned
     * on subsequent calls.
     *
     * @return ExtensionInterface|null The container extension instance, or null if not available
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new NowoPasswordToggleExtension();
        }

        return $this->extension;
    }
}
