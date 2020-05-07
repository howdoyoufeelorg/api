<?php

namespace App\DataFixtures;

use App\Entity\Area;
use App\Entity\State;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const EDITOR_USER_REFERENCE = 'editor-user';

    private $passwordEncoder;

    private $areas = [
        'Michigan 48', 'Texas 78'
    ];

    private $states = [
        'Michigan', 'Texas'
    ];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@howdoyoufeel.org');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'TestHowDoYouFeel'));
        $user->setFirstname('Admin');
        $user->setLastname('Test');
        $user->setRoles([User::ROLE_ADMIN]);
        $manager->persist($user);

        $user = new User();
        $user->setEmail('editor@howdoyoufeel.org');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'TestHowDoYouFeel'));
        $user->setFirstname('Editor');
        $user->setLastname('Test');
        $user->setRoles([User::ROLE_EDITOR]);
        foreach($this->states as $stateName) {
            $state = $manager->getRepository(State::class)->findOneBy(['name' => $stateName]);
            $user->addState($state);
        }
        foreach($this->areas as $areaName) {
            $area = $manager->getRepository(Area::class)->findOneBy(['name' => $areaName]);
            $user->addArea($area);
        }
        $manager->persist($user);
        $this->addReference(self::EDITOR_USER_REFERENCE, $user);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UsGeoEntitiesFixtures::class
        ];
    }
}
