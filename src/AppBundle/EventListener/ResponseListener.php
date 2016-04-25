<?php
/**
 * Date: 25.04.16
 * Time: 18:20
 */

namespace AppBundle\EventListener;


use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{
    const HEADER_KEY_CACHE_CONTROL = 'Cache-Control';

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        if ($request->isXmlHttpRequest()) {
            $response = $event->getResponse();
            $response->headers->add([self::HEADER_KEY_CACHE_CONTROL => 'no-store']);
        }
    }
}