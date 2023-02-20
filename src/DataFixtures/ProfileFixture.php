<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Profile;

class ProfileFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profile = new Profile();
        $profile->setRs('Facebook');
        $profile->setJob(' ');
        $profile->setUrl('https://www.facebook.com/weahpit');

        $profile1 = new Profile();
        $profile1->setRs('Twitter');
        $profile1->setJob(' ');
        $profile1->setUrl('https://www.twitter.com/weahpit');

        $profile2 = new Profile();
        $profile2->setRs('LinkedIn');
        $profile2->setJob(' ');
        $profile2->setUrl('https://www.linkedin.com/weahpit');

        $profile3 = new Profile();
        $profile3->setRs('Github');
        $profile3->setJob(' ');
        $profile3->setUrl('https://www.github.com/weahpit');

        $manager->persist($profile);
        $manager->persist($profile1);
        $manager->persist($profile2);
        $manager->persist($profile3);
        $manager->flush();
    }
}
