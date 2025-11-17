<?php
namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        // Log ALL exceptions (HTTP ones & generic ones)
        if ($exception instanceof HttpExceptionInterface) {
            $this->logger->error('HTTP ERROR', [
                'status_code' => $exception->getStatusCode(),
                'message'     => $exception->getMessage(),
                'path'        => $event->getRequest()->getPathInfo(),
            ]);
        } else {
            $this->logger->error('SERVER ERROR (500)', [
                'message' => $exception->getMessage(),
                'path'    => $event->getRequest()->getPathInfo(),
            ]);
        }

        // DO NOT set a response.
        // DO NOT override anything here.
        // Symfony will automatically load the right Twig template.
    }
}
