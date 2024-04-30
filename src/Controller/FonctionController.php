<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Product;
use App\Repository\CategorieRepository;
use App\Repository\ProductRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Request; // Assurez-vous d'importer la classe Request depuis le composant HttpFoundation
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FonctionController extends AbstractController
{
    #[Route('/fonction', name: 'app_fonction')]
    public function index(): Response
    {
        return $this->render('fonction/index.html.twig', [
            'controller_name' => 'FonctionController',
        ]);
    }

/**
     * @Route("/listArb", name="listArb", methods={"GET"})
     */
    public function list(CategorieRepository $FootRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfoptions = new Options();
        $pdfoptions->set('defaultFont', 'Arial');
        $pdfoptions->set('tempDir', '.\www\DaryGym\public\uploads\images');


        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfoptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('categorie/list.html.twig', [
            'categorie' => $FootRepository->findAll(),
        ]);
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }
  


     /**
     * @Route("/TrierspcASC", name="triespc",methods={"GET"})
     */
    public function trierSpecialite(Request $request, CategorieRepository $categorieRepository): Response
    {
        // Utilisez directement la méthode trie() du repository
        $arb = $categorieRepository->trie();

        return $this->render('categorie/index.html.twig', [
            'categories' => $arb,
        ]);
    }

 /**
     * @Route("/TrierspcDESC", name="triespcDESC",methods={"GET"})
     */
    public function trierSp(Request $request, CategorieRepository $categorieRepository): Response
    {
        // Utilisez directement la méthode trie() du repository
        $arb = $categorieRepository->trieDes();

        return $this->render('categorie/index.html.twig', [
            'categories' => $arb,
        ]);
    }

 /**
     * @Route("/Trieprix", name="trieprix",methods={"GET"})
     */
    public function trierprod(Request $request, ProductRepository $categorieRepository): Response
    {
        // Utilisez directement la méthode trie() du repository
        $arb = $categorieRepository->trieprod();

        return $this->render('product/index.html.twig', [
            'products' => $arb,
        ]);
    }


    /**
     * @Route("/Trieprixdes", name="trieprixdes",methods={"GET"})
     */
    public function trierproddes(Request $request, ProductRepository $categorieRepository): Response
    {
        // Utilisez directement la méthode trie() du repository
        $arb = $categorieRepository->trieproddes();

        return $this->render('product/index.html.twig', [
            'products' => $arb,
        ]);
    }




    
 /**
     * @Route("/search", name="recherchearb",methods={"GET"})
     */
    public function searchoffreajax(Request $request, ProductRepository $categorieRepository): Response
    {
        $requestString = $request->get('searchValue');

        // Utilisez directement la méthode trie() du repository
        $arb = $categorieRepository->findbyNom($requestString);

        return $this->render('product/index.html.twig', [
            'categories' => $arb,
        ]);
    }



    
/**
     * @Route("/stats", name="stats")
     */
    public function statistiques(ProductRepository $footRepo){ //stat tekhdem f product
        // On va chercher toutes les menus
        $menus = $footRepo->findAll(); //bsh tekhdem aal kol matel9ah f woset tableau

//Data Category
        $foot = $footRepo->createQueryBuilder('a')
            ->select('count(a.id)')
            ->Where('a.price= :price')
            ->setParameter('price',"10.10")
            ->getQuery()
            ->getSingleScalarResult();

        $hand = $footRepo->createQueryBuilder('a') // methode utilise par doctrine pour créer des requêtes sql
            ->select('count(a.id)')
            ->Where('a.price= :price')
            ->setParameter('price',"11.11")
            ->getQuery() //convertit le QueryBuilder en un objet Query utilisable par Doctrine
            ->getSingleScalarResult();//nestaamelouha f doctrine pour excuter o trajaalik un valeur
        $volley= $footRepo->createQueryBuilder('a')
            ->select('count(a.id)')
            ->Where('a.price= :price')
            ->setParameter('price',"12.12")
            ->getQuery()
            ->getSingleScalarResult();

       

        return $this->render('Stats/stats.html.twig', [
            'nfoot' => $foot,
            'nhand' => $hand,
            'nvol' => $volley,


        ]);
    }


}
