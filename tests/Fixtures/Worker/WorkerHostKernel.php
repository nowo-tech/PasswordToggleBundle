<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Tests\Fixtures\Worker;

use Nowo\PasswordToggleBundle\NowoPasswordToggleBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\UX\Icons\UXIconsBundle;

use function dirname;
use function sys_get_temp_dir;

/**
 * Host kernel with the bundle, one route and one controller that owns the host form.
 */
final class WorkerHostKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', false);
    }

    public function registerBundles(): iterable
    {
        return [new FrameworkBundle(), new TwigBundle(), new UXIconsBundle(), new NowoPasswordToggleBundle()];
    }

    public function getProjectDir(): string
    {
        return dirname(__DIR__, 3);
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/nowo-passwordtoggle-worker-kept-alive/cache';
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/nowo-passwordtoggle-worker-kept-alive/log';
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->extension('framework', [
            'test'                  => true,
            'secret'                => 'test-only-secret',
            'http_method_override'  => false,
            'handle_all_throwables' => true,
            'php_errors'            => ['log' => true],
            'csrf_protection'       => false,
        ]);
        $container->extension('twig', ['default_path' => __DIR__]);
        $container->services()
            ->set(WorkerHostController::class)
            ->autowire()
            ->public()
            ->tag('controller.service_arguments');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->add('worker_host', '/host/{who}')->controller(WorkerHostController::class);
    }
}
