<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Personne;
use App\Form\PersonneType;
use App\Services\UploaderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/personne')]
class PersonneController extends AbstractController
{
    #[Route('/liste', name: 'personne')]
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


    #[Route('/add-personne', name: 'personne.add')]
    public function addPersonne(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {


        $personne = new Personne();

        $form = $this->createForm(PersonneType::class, $personne);
        $form->remove('createdAt');
        $form->remove('updatedAt');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('personnes_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $personne->setImage($newFilename);
            }

            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $manager->flush();

            $this->addFlash('success','La personne vient dêtre enregistrée');
            return $this->redirectToRoute("personne");
        } else {
            return $this->render('personne/add-personne.html.twig',[
                'form' =>$form->createView()
            ]);
        }
    }

    #[Route('/edit/{id?0}', name: 'personne.edit')]
    public function editPersonne(
        Personne $personne = null,
        ManagerRegistry $doctrine,
        Request $request,
        UploaderService $uploaderService): Response
    {

        if(!$personne){
            $personne = new Personne();
        }

        $form = $this->createForm(PersonneType::class, $personne);
        $form->remove('createdAt');
        $form->remove('updatedAt');

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ){

            $photo = $form->get('photo')->getData();

            if ($photo) {

                $directory = $this->getParameter('personnes_directory');

                $personne->setImage($uploaderService->uploadFile($photo,  $directory));
            }


            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $manager->flush();

            $this->addFlash('success','La personne vient dêtre édité avec succès');
            return $this->redirectToRoute("personne");
        } else {
            return $this->render('personne/add-personne.html.twig',[
                'form' =>$form->createView()
            ]);
        }
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
