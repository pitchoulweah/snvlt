<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Tests\Models\Cache\Person;
use App\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/personne')]
class PersonneController extends AbstractController
{
    #[Route('/', name: 'personne')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();
        return $this->render('personne/index.html.twig',[
            'liste_personne'=>$personnes
        ]);
    }

    #[Route('/{id<\d+>}', name: 'personne.detail')]
    public function detail(Personne $personne = null): Response
    {

            if(!$personne) {

                $this->addFlash('error', "La personne n'existe pas");
                return $this->redirectToRoute('personne');
            }

        return $this->render('personne/detail.html.twig',['personne'=>$personne]);

    }

    #[Route('/add', name: 'personne.add')]
    public function add(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $personne = new Personne();
            $personne->setFirstname('Léa');
            $personne->setLastname('YAO');
            $personne->setAge(32);
            $personne->setJob('Opératrice de saisie');

        $entityManager->persist($personne);
        $entityManager->flush();
        return $this->render('personne/add.html.twig',[
        'personne'=> $personne
            ]);
    }

    #[Route('/alls/{page?1}/{nbre?12}', name: 'personne.alls')]
    public function alls(ManagerRegistry $doctrine, $page, $nbre): Response
    {

        $repository = $doctrine->getRepository(Personne::class);
        $nbPersonnes = $repository->count([]);
        $nbPages = ceil($nbPersonnes / $nbre);
        $personnes = $repository->findBy([], [], $nbre, ($page-1) * $nbre);
        return $this->render('personne/index.html.twig',[
            'liste_personne'=>$personnes,
            'isPaginated'=>true,
            'nbrePages'=>$nbPages,
            'page'=>$page,
            'nbre'=>$nbre

        ]);
    }

    #[Route('/delete/{id<\d+>}', name: 'personne.delete')]
    public function deletePersonne(Personne $personne = null,  ManagerRegistry $doctrine): RedirectResponse
    {

        if($personne) {
            $manager = $doctrine->getManager();

            $manager->remove($personne);

            $manager->flush();
            $this->addFlash('success', "La personne a été supprimée avec succès");
        } else {
            $this->addFlash('error', "Personne innexsistante");
        }
        return $this->redirectToRoute('personne.alls');

    }

    #[Route('/update/{id<\d+>}/{firstname}/{lastname}/{age}', name: 'personne.update')]
    public function updatePersonne(Personne $personne = null,  ManagerRegistry $doctrine, $firstname, $lastname, $age): RedirectResponse
    {

        if($personne) {
            $personne->setFirstname($firstname);
            $personne->setLastname($lastname);
            $personne->setAge($age);

            $manager = $doctrine->getManager();

            $manager->persist($personne);

            $manager->flush();
            $this->addFlash('success', "La personne a été modifiée avec succès");
        } else {
            $this->addFlash('error', "Personne innexsistante");
        }
        return $this->redirectToRoute('personne.alls');

    }


    #[Route('/age/{agemin}/{agemax}', name: 'personne.age')]
    public function personneByAge( ManagerRegistry $doctrine, $agemin, $agemax): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findPersonneByAgeInterval($agemin, $agemax);
        return $this->render('personne/index.html.twig',[
            'liste_personne'=>$personnes
        ]);

    }

    #[Route('/age/stats/{agemin}/{agemax}', name: 'personne.age.stats')]
    public function statsPersonneByAge( ManagerRegistry $doctrine, $agemin, $agemax): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $stats = $repository->statsPersonneByAgeInterval($agemin, $agemax);
        return $this->render('personne/stats.html.twig',[
            'stats'=>$stats[0],
            
            'ageMin'=>$agemin,
            'ageMax'=>$agemax
        ]);

    }
}
