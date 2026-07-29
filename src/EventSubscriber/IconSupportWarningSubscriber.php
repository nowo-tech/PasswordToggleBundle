<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\EventSubscriber;

use Nowo\PasswordToggleBundle\IconSupport\IconSupportChecker;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Service\ResetInterface;

/**
 * Logs a one-time warning when icon dependencies are missing (does not block cache warmup).
 *
 * Instance state + ResetInterface keeps the one-shot guard safe under FrankenPHP worker.
 */
final class IconSupportWarningSubscriber implements EventSubscriberInterface, ResetInterface
{
    private bool $warned = false;

    public function __construct(
        private readonly IconSupportChecker $iconSupportChecker,
        private readonly LoggerInterface $logger,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST  => ['onKernelRequest', 1024],
            ConsoleEvents::COMMAND => ['onConsoleCommand', 1024],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $this->warnIfNeeded();
    }

    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $this->warnIfNeeded();
    }

    /**
     * Resets the one-time guard (tests / kernel.reset).
     */
    public function reset(): void
    {
        $this->warned = false;
    }

    private function warnIfNeeded(): void
    {
        if ($this->warned) {
            return;
        }

        $message = $this->iconSupportChecker->getActionableWarningMessage();

        if ($message === null) {
            return;
        }

        $this->warned = true;
        $this->logger->warning($message, ['channel' => 'nowo_password_toggle']);
    }
}
