<?php

namespace App\EventListener;

use App\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class ExceptionListener
 * @package App\EventListener
 */
class ExceptionListener
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof NotFoundException) {
            $event->setResponse(new JsonResponse([
                'message' => $exception->getMessage(),
                'status' => 'error'
            ], 404));
        }
    }
}
