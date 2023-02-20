<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserFixture extends Fixture implements FixtureGroupInterface
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('ndiaabdoulaziz07@yahoo.fr');
        $user1->setPassword($this->hasher->hashPassword($user1, '020780'));
        $user1->setRoles(['ROLE_ADMIN']);

        $user2 = new User();
        $user2->setEmail('aziz.ndia@outlook.com');
        $user2->setPassword($this->hasher->hashPassword($user2, 'user'));
        $user1->setRoles(['ROLE_ADMIN']);


        $manager->persist($user1);
        $manager->persist($user2);

        for ($i=1;$i<6; $i++){
            $user = new User();
            $user->setEmail("user$i@outlook.com");
            $user->setPassword($this->hasher->hashPassword($user2, $i));
            $manager->persist($user);
        }
        $manager->flush();

    }
    public static function getGroups() : array {
        return ['user'];
    }
}
