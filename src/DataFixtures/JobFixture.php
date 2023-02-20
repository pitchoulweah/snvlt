<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Job;

class JobFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            "Data Analyste",
            "Mécanicien",
            "Statisticien",
            "Analyste cyber-sécurité",
            "Medecin ORL",
            "Echographiste",
            "Mathématicien",
            "Ingénieur Logiciel",
            "Pathologiste du discours langage",
            "Ergothérapeute",
            "Hygiéniste deentaire",
            "Directeur des Ressources Humaines"
        ];
        for($i=0;$i<count($data);$i++){
            $job = new Job();
            $job->setDesignation($data[$i]);
            $manager->persist($job);
        }
            $manager->flush();
    }
}
