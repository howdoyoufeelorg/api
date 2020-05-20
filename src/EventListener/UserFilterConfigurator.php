<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 13/04/2020
 * Time: 3:29 pm
 */

namespace App\EventListener;

use App\Helper\Security;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Annotations\Reader;

final class UserFilterConfigurator
{
    private $em;
    private $tokenStorage;
    private $reader;
    /**
     * @var Security
     */
    private $security;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, Reader $reader, Security $security)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->reader = $reader;
        $this->security = $security;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $path = $event->getRequest()->getPathInfo();
        if(preg_match('/\/api/', $path)) {
            if (!$user = $this->getUser()) {
                throw new \RuntimeException('There is no authenticated user.');
            }
            if (!$this->security->isAdmin()) {
                $filter = $this->em->getFilters()->enable('user_filter');
                $filter->setParameter('id', $user->getId());
                $filter->setAnnotationReader($this->reader);
            }
        }
    }

    private function getUser(): ?UserInterface
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return null;
        }

        $user = $token->getUser();
        return $user instanceof UserInterface ? $user : null;
    }
}