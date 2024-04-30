<?php

namespace App\Controller;
use App\Entity\Panier;
use App\Repository\PanierRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'name' => 'chaima',
        ]);
    }


    #[Route('/panier', name: 'app_panier')]
    public function panier(PanierRepository $panierRepository ): Response
    {  
        $nombreDePaniers = $panierRepository->count([]);
        
        // Rendre la variable accessible globalement dans les templates Twig
        $this->get('twig')->addGlobal('nombreDePaniers', $nombreDePaniers);

        return $this->render('front.html.twig');
    }
}
