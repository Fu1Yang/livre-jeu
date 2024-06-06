<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LivreJeuController extends AbstractController
{
    #[Route('/livre/jeu', name: 'app_livre_jeu')]
    public function index(): Response
    {
        return $this->render('livre_jeu/index.html.twig', [
            'controller_name' => 'LivreJeuController',
        ]);
    }
}
