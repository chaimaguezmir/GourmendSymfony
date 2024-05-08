<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductRating;
use App\Form\ProductRatingType;
use App\Repository\ProductRatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PanierRepository;

#[Route('/product/rating')]
class ProductRatingController extends AbstractController
{
    #[Route('/', name: 'app_product_rating_index', methods: ['GET'])]
    public function index(ProductRatingRepository $productRatingRepository): Response
    {
        return $this->render('product_rating/index.html.twig', [
            'product_ratings' => $productRatingRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_product_rating_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager ,PanierRepository $panierRepository): Response
    {$nombreDePaniers = $panierRepository->count([]);
        
        // Rendre la variable accessible globalement dans les templates Twig
        $this->get('twig')->addGlobal('nombreDePaniers', $nombreDePaniers);

        $productid = $request->get('id');
        $product = $entityManager->getRepository(Product::class)->find($productid);

        $productRating = new ProductRating();
        $form = $this->createForm(ProductRatingType::class, $productRating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRating->setProduct($product);  
            $entityManager->persist($productRating);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_rating/new.html.twig', [
            'product_rating' => $productRating,
            'form' => $form,
        ]);
    }
/*
    #[Route('/{id}', name: 'app_product_rating_show', methods: ['GET'])]
    public function show(ProductRating $productRating): Response
    {
        return $this->render('product_rating/show.html.twig', [
            'product_rating' => $productRating,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_rating_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProductRating $productRating, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductRatingType::class, $productRating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_product_rating_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_rating/edit.html.twig', [
            'product_rating' => $productRating,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_rating_delete', methods: ['POST'])]
    public function delete(Request $request, ProductRating $productRating, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productRating->getId(), $request->request->get('_token'))) {
            $entityManager->remove($productRating);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_rating_index', [], Response::HTTP_SEE_OTHER);
    }*/
}
