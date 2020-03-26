<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('viktor.kostadinov@gmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'Yamdx7fd'));
        $user->setFirstname('Viktor');
        $user->setLastname('Kostadinov');
        $user->setRoles(['ROLE_SUPERADMIN']);
        $manager->persist($user);

        $user = new User();
        $user->setEmail('dante@thezyx.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'Iludkmf'));
        $user->setFirstname('Dante');
        $user->setMiddlename('Carmelo');
        $user->setLastname('Cullari');
        $user->setRoles(['ROLE_SUPERADMIN']);
        $manager->persist($user);

        $manager->flush();
    }
}
