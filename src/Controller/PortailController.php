<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortailController extends AbstractController
{
    #[Route('/', name: 'app_portail')]
    public function index(): Response
    {
        return $this->render('portail/index.html.twig', [
            'controller_name' => 'PortailController',
        ]);
    }
}
