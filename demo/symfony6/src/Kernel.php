<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Demo application kernel.
 *
 * This is the kernel class for the demo application that demonstrates
 * the Password Toggle Bundle functionality.
 *
 * @author Héctor Franco Aceituno <hectorfranco@nowo.tech>
 * @copyright 2026 Nowo.tech
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}

