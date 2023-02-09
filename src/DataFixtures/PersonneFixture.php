<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

use App\Entity\Personne;

class PersonneFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
     for($i=0; $i<100; $i++)
     {
         $faker= Factory::create('fr_FR');
         $personne = new Personne();
         $personne->setFirstname($faker->firstName);
         $personne->setLastname($faker->name);
         $personne->setAge($faker->numberBetween(18,65));
         $personne->setJob($faker->company);

         $manager->persist($personne);
     }
        $manager->flush();
    }
}
