<?php

namespace App;

use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    protected function initializeContainer()
    {
        parent::initializeContainer();

        // If debug mode is ON, Symfony uses the "blue screen"
        // We disable it so custom error pages show even in dev.
        if ($this->debug) {
            restore_error_handler();
            restore_exception_handler();

            // Register the normal production error handler
            ErrorHandler::register(null, false);
        }
    }
}
