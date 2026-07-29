<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Tests\Unit\EventSubscriber;

use Nowo\PasswordToggleBundle\EventSubscriber\IconSupportWarningSubscriber;
use Nowo\PasswordToggleBundle\IconSupport\IconSupportChecker;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class IconSupportWarningSubscriberTest extends TestCase
{
    public function testLogsWarningOnceWhenDependenciesMissing(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('warning')
            ->with($this->stringContains('symfony/ux-icons'));

        $subscriber = new IconSupportWarningSubscriber(
            new IconSupportChecker(uxIconsAvailable: false, httpClientAvailable: false),
            $logger,
        );

        $kernel       = $this->createMock(HttpKernelInterface::class);
        $requestEvent = new RequestEvent($kernel, Request::create('/'), HttpKernelInterface::MAIN_REQUEST);
        $subscriber->onKernelRequest($requestEvent);
        $subscriber->onKernelRequest($requestEvent);

        $commandEvent = new ConsoleCommandEvent(null, new ArrayInput([]), new NullOutput());
        $subscriber->onConsoleCommand($commandEvent);
    }

    public function testSubscribedEvents(): void
    {
        $this->assertArrayHasKey(KernelEvents::REQUEST, IconSupportWarningSubscriber::getSubscribedEvents());
        $this->assertArrayHasKey(ConsoleEvents::COMMAND, IconSupportWarningSubscriber::getSubscribedEvents());
    }

    public function testIgnoresSubRequests(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->never())->method('warning');

        $subscriber = new IconSupportWarningSubscriber(
            new IconSupportChecker(uxIconsAvailable: false, httpClientAvailable: false),
            $logger,
        );

        $kernel = $this->createMock(HttpKernelInterface::class);
        $subscriber->onKernelRequest(new RequestEvent($kernel, Request::create('/'), HttpKernelInterface::SUB_REQUEST));
    }

    public function testResetAllowsSecondWarning(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->exactly(2))->method('warning');

        $subscriber = new IconSupportWarningSubscriber(
            new IconSupportChecker(uxIconsAvailable: false, httpClientAvailable: false),
            $logger,
        );

        $kernel = $this->createMock(HttpKernelInterface::class);
        $event  = new RequestEvent($kernel, Request::create('/'), HttpKernelInterface::MAIN_REQUEST);
        $subscriber->onKernelRequest($event);
        $subscriber->reset();
        $subscriber->onKernelRequest($event);
    }

    public function testDoesNotLogWhenDependenciesPresent(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->never())->method('warning');

        $subscriber = new IconSupportWarningSubscriber(
            new IconSupportChecker(uxIconsAvailable: true, httpClientAvailable: true),
            $logger,
        );

        $kernel = $this->createMock(HttpKernelInterface::class);
        $subscriber->onKernelRequest(new RequestEvent($kernel, Request::create('/'), HttpKernelInterface::MAIN_REQUEST));
    }
}
