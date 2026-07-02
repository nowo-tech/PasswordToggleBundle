<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\IconSupport;

use Closure;

use function sprintf;

/**
 * Detects whether Symfony UX Icons and HTTP Client are available for the default widget.
 */
final readonly class IconSupportChecker
{
    private const UX_ICONS_RUNTIME_CLASSES = [
        \Symfony\UX\Icons\Twig\UXIconRuntime::class,
        'Symfony\UX\Icons\Twig\UXIconsRuntime',
    ];

    private const HTTP_CLIENT_INTERFACE = \Symfony\Contracts\HttpClient\HttpClientInterface::class;

    public function __construct(
        private ?bool $uxIconsAvailable = null,
        private ?bool $httpClientAvailable = null,
        /** @var Closure(string): bool|null Test seam; Symfony must not inject this argument. */
        private ?Closure $classExistsChecker = null,
    ) {
    }

    public function isUxIconsAvailable(): bool
    {
        if ($this->uxIconsAvailable !== null) {
            return $this->uxIconsAvailable;
        }

        foreach (self::UX_ICONS_RUNTIME_CLASSES as $class) {
            if ($this->runtimeClassExists($class)) {
                return true;
            }
        }

        return false;
    }

    private function runtimeClassExists(string $class): bool
    {
        if ($this->classExistsChecker instanceof Closure) {
            return ($this->classExistsChecker)($class);
        }

        return class_exists($class);
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
