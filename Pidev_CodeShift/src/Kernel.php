<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Knp\Bundle\SnappyBundle\KnpSnappyBundle;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
