<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\IconSupport;

use function sprintf;

/**
 * Detects whether Symfony UX Icons and HTTP Client are available for the default widget.
 */
final class IconSupportChecker
{
    private const UX_ICONS_RUNTIME_CLASS = 'Symfony\UX\Icons\Twig\UXIconsRuntime';

    private const HTTP_CLIENT_INTERFACE = 'Symfony\Contracts\HttpClient\HttpClientInterface';

    public function __construct(
        private ?bool $uxIconsAvailable = null,
        private ?bool $httpClientAvailable = null,
    ) {
    }

    public function isUxIconsAvailable(): bool
    {
        return $this->uxIconsAvailable ?? class_exists(self::UX_ICONS_RUNTIME_CLASS);
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
            'Password Toggle Bundle default icons require %s. Run: composer require %s && php bin/console ux:icons:lock tabler:eye tabler:eye-off',
            implode(' and ', $missing),
            implode(' ', $missing),
        );
    }
}
