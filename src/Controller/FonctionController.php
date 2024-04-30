<?php

namespace App\Controller;

use App\Entity\Reservation;


use App\Repository\ReservationRepository;

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
     * @Route("/TrierspcASC", name="triespc",methods={"GET"})
     */
    public function trierSpecialite(Request $request, ReservationRepository $ReservationRepository): Response
    {
        // Utilisez directement la méthode trie() du repository
        $arb = $ReservationRepository->trie();

        return $this->render('reservation/index.html.twig', [
            'reservations' => $arb,
        ]);
    }

 /**
     * @Route("/TrierspcDESC", name="triespcDESC",methods={"GET"})
     */
    public function trierSp(Request $request, ReservationRepository $reservationRepository): Response
    {
        // Utilisez directement la méthode trie() du repository
        $arb = $reservationRepository->trieDes();

        return $this->render('reservation/index.html.twig', [
            'reservations' => $arb,
        ]);
    }

 /**
     * @Route("/Trieprix", name="trieprix",methods={"GET"})
     */
    public function trierprode(Request $request, ReservationRepository $reservationRepository): Response
    {
        // Utilisez directement la méthode trie() du repository
        $arb = $reservationRepository->trierreservation();

        return $this->render('reservation/index.html.twig', [
            'reservation' => $arb,
        ]);
    }


    // /**
    //  * @Route("/Trieprixdes", name="trieprixdes",methods={"GET"})
    //  */
    // public function trierproddes(Request $request, ProductRepository $categorieRepository): Response
    // {
    //     // Utilisez directement la méthode trie() du repository
    //     $arb = $categorieRepository->trieproddes();

    //     return $this->render('product/index.html.twig', [
    //         'products' => $arb,
    //     ]);
    // }




    


    
// /**
//      * @Route("/stats", name="stats")
//      */
//     public function statistiques(RepasRepository $footRepo){
//         // On va chercher toutes les menus
//         $menus = $footRepo->findAll();

// //Data Category
//         $foot = $footRepo->createQueryBuilder('a')
//             ->select('count(a.id)')
//             ->Where('a.type= :type')
//             ->setParameter('type',"client")
//             ->getQuery()
//             ->getSingleScalarResult();

//         $hand = $footRepo->createQueryBuilder('a')
//             ->select('count(a.id)')
//             ->Where('a.type= :type')
//             ->setParameter('type',"admin")
//             ->getQuery()
//             ->getSingleScalarResult();
//         $volley= $footRepo->createQueryBuilder('a')
//             ->select('count(a.id)')
//             ->Where('a.type= :type')
//             ->setParameter('type',"hhhhh")
//             ->getQuery()
//             ->getSingleScalarResult();

       

//         return $this->render('Stats/stats.html.twig', [
//             'nfoot' => $foot,
//             'nhand' => $hand,
//             'nvol' => $volley,


//         ]);
//     }


}