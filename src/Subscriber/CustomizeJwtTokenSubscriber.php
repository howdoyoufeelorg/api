<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 13/04/2020
 * Time: 11:38 am
 */

namespace App\Subscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Events as JWTEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CustomizeJwtTokenSubscriber implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            JWTEvents::JWT_CREATED => ['addCustomData'],
        ];
    }

    public function addCustomData(JWTCreatedEvent $event)
    {
        $payload = $event->getData();
        $payload['id'] = $this->tokenStorage->getToken()->getUser()->getId();
        $event->setData($payload);
    }
}