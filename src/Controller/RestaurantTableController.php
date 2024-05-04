<?php

namespace App\Controller;

use App\Entity\RestaurantTable;
use App\Form\RestaurantTableType;
use App\Repository\RestaurantTableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/restaurant/table')]
class RestaurantTableController extends AbstractController
{
    #[Route('/', name: 'app_restaurant_table_index', methods: ['GET'])]
    public function index(RestaurantTableRepository $restaurantTableRepository): Response
    {
        return $this->render('restaurant_table/index.html.twig', [
            'restaurant_tables' => $restaurantTableRepository->findAll(),
            
        ]);
    }










    #[Route('/frontres', name: 'frontres', methods: ['GET'])]
    public function frontres(RestaurantTableRepository $productRepository): Response
    {
        return $this->render('restaurant_table/indexfront.html.twig', [
            'restaurant_table' => $productRepository->findAll(),
        ]);
    }



    #[Route('/new', name: 'app_restaurant_table_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $restaurantTable = new RestaurantTable();
        $form = $this->createForm(RestaurantTableType::class, $restaurantTable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($restaurantTable);
            $entityManager->flush();

            return $this->redirectToRoute('app_restaurant_table_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('restaurant_table/new.html.twig', [
            'restaurant_table' => $restaurantTable,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_restaurant_table_show', methods: ['GET'])]
    public function show(RestaurantTable $restaurantTable): Response
    {
        return $this->render('restaurant_table/show.html.twig', [
            'restaurant_table' => $restaurantTable,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_restaurant_table_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RestaurantTable $restaurantTable, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RestaurantTableType::class, $restaurantTable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_restaurant_table_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('restaurant_table/edit.html.twig', [
            'restaurant_table' => $restaurantTable,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_restaurant_table_delete', methods: ['POST'])]
    public function delete(Request $request, RestaurantTable $restaurantTable, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restaurantTable->getId(), $request->request->get('_token'))) {
            $entityManager->remove($restaurantTable);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_restaurant_table_index', [], Response::HTTP_SEE_OTHER);
    }

















}
