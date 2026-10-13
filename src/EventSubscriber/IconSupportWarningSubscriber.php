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

/**
 * Logs a one-time warning when icon dependencies are missing (does not block cache warmup).
 *
 * The one-shot guard deliberately does not implement ResetInterface: installed packages
 * cannot change while a process runs, so the warning is logged once per process (PHP-FPM
 * request, console command or FrankenPHP worker) instead of on every request after
 * `kernel.reset`. Never stores request or user data.
 */
final class IconSupportWarningSubscriber implements EventSubscriberInterface
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

    private function warnIfNeeded(): void
    {
        if ($this->warned) {
            return;
        }

        $message = $this->iconSupportChecker->getActionableWarningMessage();

        if ($message === null) {
            return;
        }

        // @igor-ignore - Process-wide log de-duplication flag (no request/user data); must survive kernel.reset.
        $this->warned = true;
        $this->logger->warning($message, ['channel' => 'nowo_password_toggle']);
    }
}
