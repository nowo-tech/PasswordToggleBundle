<?php

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\UX\Icon\IconBundle;
use Nowo\PasswordToggleBundle\NowoPasswordToggleBundle;

return [
    FrameworkBundle::class => ['all' => true],
    TwigBundle::class => ['all' => true],
    IconBundle::class => ['all' => true],
    NowoPasswordToggleBundle::class => ['all' => true],
];
