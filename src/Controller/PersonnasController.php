<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/personnas', name: 'app_personnas_')]
class PersonnasController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('personnas/index.html.twig');
    }

    #[Route('/free-sub', name: 'free-sub')]
    public function freeSub(): Response
    {
        
        return $this->render('personnas/free-sub.html.twig');
    }

    #[Route('/premium-sub', name: 'premium-sub')]
    public function premiumSub(): Response
    {
        
        return $this->render('personnas/premium-sub.html.twig');
    }
}
