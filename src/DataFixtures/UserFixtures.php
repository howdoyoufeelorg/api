<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const EDITOR_USER_REFERENCE = 'editor-user';

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('test@howdoyoufeel.org');
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
        $manager->persist($user);
        $this->addReference(self::EDITOR_USER_REFERENCE, $user);

        $manager->flush();
    }
}
