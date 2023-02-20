<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Hobbie;

class HobbieFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            "Pêche",
            "Lecture",
            "Sport",
            "Pétanque",
            "FootballL",
            "Ballade",
            "Informatique"
        ];
        for($i=0; $i< count($data); $i++){
            $hobby = new Hobbie();
            $hobby->setDesignation($data[$i]);
            $hobby->setProfile(' ');
            $manager->persist($hobby);
        }
        $manager->flush();
    }
}
