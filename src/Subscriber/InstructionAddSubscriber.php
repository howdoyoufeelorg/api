<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 08/04/2020
 * Time: 12:14 pm
 */

namespace App\Subscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Instruction;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class InstructionAddSubscriber implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addCreatedBy', EventPriorities::PRE_WRITE],
        ];
    }

    public function addCreatedBy(ViewEvent $event): void
    {
        $instruction = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if (!$instruction instanceof Instruction || Request::METHOD_POST !== $method) {
            return;
        }
        $instruction->setCreatedBy($this->tokenStorage->getToken()->getUser());
        $event->setControllerResult($instruction);
    }
}