<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\IconSupport;

use function sprintf;

/**
 * Detects whether Symfony UX Icons and HTTP Client are available for the default widget.
 */
final class IconSupportChecker
{
    private const UX_ICONS_RUNTIME_CLASSES = [
        \Symfony\UX\Icons\Twig\UXIconRuntime::class,
        'Symfony\UX\Icons\Twig\UXIconsRuntime',
    ];

    private const HTTP_CLIENT_INTERFACE = \Symfony\Contracts\HttpClient\HttpClientInterface::class;

    private const UX_ICONS_RENDERER_INTERFACE = \Symfony\UX\Icons\IconRendererInterface::class;

    public function __construct(
        private readonly ?bool $uxIconsAvailable = null,
        private readonly ?bool $httpClientAvailable = null,
    ) {
    }

    public function isUxIconsAvailable(): bool
    {
        if ($this->uxIconsAvailable !== null) {
            return $this->uxIconsAvailable;
        }

        foreach (self::UX_ICONS_RUNTIME_CLASSES as $class) {
            if (class_exists($class)) {
                return true;
            }
        }

        return interface_exists(self::UX_ICONS_RENDERER_INTERFACE);
    }

    public function isHttpClientAvailable(): bool
    {
        return $this->httpClientAvailable ?? interface_exists(self::HTTP_CLIENT_INTERFACE);
    }

    public function isIconRenderingSupported(): bool
    {
        return $this->isUxIconsAvailable() && $this->isHttpClientAvailable();
    }

    /**
     * @return list<string>
     */
    public function getMissingPackages(): array
    {
        $missing = [];

        if (!$this->isUxIconsAvailable()) {
            $missing[] = 'symfony/ux-icons';
        }

        if (!$this->isHttpClientAvailable()) {
            $missing[] = 'symfony/http-client';
        }

        return $missing;
    }

    public function getActionableWarningMessage(): ?string
    {
        $missing = $this->getMissingPackages();

        if ($missing === []) {
            return null;
        }

        return sprintf(
            'Password Toggle Bundle default icons require %s. Run: composer require %s && php bin/console ux:icons:lock',
            implode(' and ', $missing),
            implode(' ', $missing),
        );
    }
}
