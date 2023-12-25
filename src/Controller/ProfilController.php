<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil', name: 'profile_')]
class ProfilController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {

        // if(!$this->getUser())
        // {
        //     return $this->redirectToRoute('app_login');
        // }

        return $this->render('profil/index.html.twig', [
            'controller_name' => 'Profil de l\'utilisateur',
        ]);
    }

    #[Route('/commandes', name: 'orders')]
    public function orders(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'Commandes de l\'utilisateur',
        ]);
    }
}