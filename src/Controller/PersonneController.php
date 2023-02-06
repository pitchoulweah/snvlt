<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonneController extends AbstractController
{
    #[Route('/personne', name: 'app_personne')]
    public function index(Request $masession): Response

    {
        $objetSession = $masession->getSession();
        $nom = "NDIA ABDOUL AZIZ";
        $objetSession->set('nom_utilisateur', $nom);
        return $this->render('personne/index.html.twig');
    }
}
