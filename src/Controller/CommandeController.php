<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Form\CommandeTypeb;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use UltraMsg\WhatsAppApi;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }
    #[Route('/b', name: 'app_commande_indexb', methods: ['GET'])]
    public function indexb(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/indexb.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }
    #[Route('/stat', name: 'statistiques_commandes')]
    public function statistiquesCommandes(CommandeRepository $commandeRepository): Response
    {
        $nbCommandesPending = $commandeRepository->countByStatus('pending');
        $nbCommandesCompleted = $commandeRepository->countByStatus('completed');
        $nbCommandesCancelled = $commandeRepository->countByStatus('cancelled');

        return $this->render('commande/stat.html.twig', [
            'nbCommandesPending' => $nbCommandesPending,
            'nbCommandesCompleted' => $nbCommandesCompleted,
            'nbCommandesCancelled' => $nbCommandesCancelled,
        ]);
    }
/*
    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();
            $this->addFlash('success', 'La commande a été ajouté avec succès.');
            $id=$commande->getId();
            $this->envoyerMessageWhatsApp($id);

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }*/



    #[Route('/new/{somme}', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,$somme): Response
    {
        $commande = new Commande();
        $commande->setPrixTotal($somme);
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

          

        if ($form->isSubmitted() && $form->isValid()) {


           // $commande->setPanier(1);
            $commande->setIdPersonne(1);
           
            $entityManager->persist($commande);
            $entityManager->flush();
            $session = $request->getSession();
            $session->set('cart_items', []);
           
            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }


















   
    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'La commande a été modifié avec succès.');

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/editb', name: 'app_commande_editb', methods: ['GET', 'POST'])]
    public function editb(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeTypeb::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'La commande a été modifié avec succès.');

            return $this->redirectToRoute('app_commande_indexb', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/editb.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }
        $this->addFlash('success', 'La commande a été suprimé avec succès.');

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/b', name: 'app_commande_deleteb', methods: ['POST'])]
    public function deleteb(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }
        $this->addFlash('success', 'La commande a été suprimé avec succès.');

        return $this->redirectToRoute('app_commande_indexb', [], Response::HTTP_SEE_OTHER);
    }

}
