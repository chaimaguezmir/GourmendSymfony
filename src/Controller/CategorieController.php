<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PanierRepository;

#[Route('/categorie')]
class CategorieController extends AbstractController
{


    #[Route('/pdf', name: 'pdf', methods: ['GET'])]
    public function pdf(CategorieRepository $CategorieRepository): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $pathToImage = 'C:\Users\roua mtar\Downloads\pidev\GourmendSymfony-RouaMtar\public/logo.jpg';
        $imageData = base64_encode(file_get_contents($pathToImage));
        $html = $this->renderView('categorie/pdf.html.twig', [
            'categories' => $CategorieRepository->findAll(),
            'imageData' => $imageData,

        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="mypdf.pdf"',
            ]
        );
    }



    #[Route('/', name: 'app_categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {
        
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }



    #[Route('/new', name: 'app_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager ,PanierRepository $panierRepository): Response
    {
        $nombreDePaniers = $panierRepository->count([]);
        
        // Rendre la variable accessible globalement dans les templates Twig
        $this->get('twig')->addGlobal('nombreDePaniers', $nombreDePaniers);

        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Added successfully!'
            );
            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie ,PanierRepository $panierRepository): Response
    {$nombreDePaniers = $panierRepository->count([]);
        
        // Rendre la variable accessible globalement dans les templates Twig
        $this->get('twig')->addGlobal('nombreDePaniers', $nombreDePaniers);

        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager ,PanierRepository $panierRepository): Response
    {$nombreDePaniers = $panierRepository->count([]);
        
        // Rendre la variable accessible globalement dans les templates Twig
        $this->get('twig')->addGlobal('nombreDePaniers', $nombreDePaniers);

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash(
                'info',
                'Added successfully!'
            );

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager ,PanierRepository $panierRepository): Response
    {$nombreDePaniers = $panierRepository->count([]);
        
        // Rendre la variable accessible globalement dans les templates Twig
        $this->get('twig')->addGlobal('nombreDePaniers', $nombreDePaniers);

        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();

            $this->addFlash(
                'del',
                'Added successfully!'
            );
        }

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }


 








 #[Route('/catfront', name: 'app_categorie_indexx', methods: ['GET'])]
    public function indexx(CategorieRepository $categorieRepository ,PanierRepository $panierRepository): Response
    {$nombreDePaniers = $panierRepository->count([]);
        
        // Rendre la variable accessible globalement dans les templates Twig
        $this->get('twig')->addGlobal('nombreDePaniers', $nombreDePaniers);

        return $this->render('categorie/categoriefront.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }
    







 

    

}
