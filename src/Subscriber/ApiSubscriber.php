<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 06/11/2018
 * Time: 21:05
 */

namespace App\Subscriber;

use App\Controller\DefaultController;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiSubscriber implements EventSubscriberInterface
{
    public function onKernelController(ControllerEvent $event)
    {
        return;
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if($controller[0] == DefaultController::class) {
            $request = $event->getRequest();
            if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
                $data = json_decode($request->getContent(), true);
                $request->request->replace(is_array($data) ? $data : array());
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}