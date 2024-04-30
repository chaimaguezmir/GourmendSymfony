<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\RestaurantTable;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\QrCodeGenerator;



class ReservationController extends AbstractController
{

/******************calendar*******************/
 
#[Route('/indexmain', name: 'indewmain', methods: ['GET'])]
public function indexmain(ReservationRepository $ReservationRepository): Response
{
    $events = $ReservationRepository->findAll();

    $rdvs = [];

    foreach($events as $event){
        $rdvs[] = [
            'id' => $event->getId(),
            'start' => $event->getDateTime()->format('Y-m-d'),
            'title'=> "reservation  ".$event->getCustomerName()
            
        ];
    }

    $data = json_encode($rdvs);

    return $this->render('reservation/maincalander.html.twig', ['data'=> $data]);
}

#[Route('/reservation/stats', name: 'reservation_stats')]
public function stats(ReservationRepository $reservationRepository): Response
{
    $statistics = $reservationRepository->getReservationStatusCounts();
    
    return $this->render('reservation/stat.html.twig', [
        'statistics' => $statistics,  // Correction : utiliser le même nom que dans le template
    ]);
}

    

    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }



   
    #[Route('/front', name: 'front', methods: ['GET'])]
public function front(ReservationRepository $productRepository): Response
{
    return $this->render('baseFront.html.twig', [
        'products' => $productRepository->findAll(),
    ]);
}



    #[Route('/frontresv', name: 'frontresv', methods: ['GET'])]
    public function frontresv(ReservationRepository $productRepository): Response
    {
        return $this->render('reservation/indexfront.html.twig', [
            'reservations' => $productRepository->findAll(),
        ]);
    }
/*
    #[Route('/newreservation/{tableId}', name: 'app_reservation', methods: ['GET', 'POST'])]
    public function reservationfront(Request $request, EntityManagerInterface $entityManager, $tableId): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Associer la réservation à la table correspondante
            $table = $entityManager->getRepository(RestaurantTable::class)->find($tableId);
            if (!$table) {
                throw $this->createNotFoundException('La table avec l\'ID ' . $tableId . ' n\'existe pas.');
            }
            $reservation->setTableid($table);
    
            $entityManager->persist($reservation);
            $entityManager->flush();
    
            // Marquer la table comme occupée
            $table->markAsOccupied();
            $entityManager->flush();
    
            return $this->redirectToRoute('frontres', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('reservation/reserverfront.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }
    */
 /*   #[Route('/newreservation/{tableId}', name: 'app_reservation', methods: ['GET', 'POST'])]
    public function reservationfront(Request $request, EntityManagerInterface $entityManager, $tableId): Response
{
    $reservation = new Reservation();
    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $formData = $form->getData();
        $chosenDateTime = $formData->getDateTime();

        $existingReservation = $entityManager->getRepository(Reservation::class)->findOneBy([
            'dateTime' => $chosenDateTime,
        ]);

        if ($existingReservation) {
            $this->addFlash('error', 'This date and time is already reserved.');
            return $this->renderForm('reservation/reserverfront.html.twig', [
                'reservation' => $reservation,
                'form' => $form,
            ]);            
        }

        $table = $entityManager->getRepository(RestaurantTable::class)->find($tableId);
        if (!$table) {
            throw $this->createNotFoundException('La table avec l\'ID ' . $tableId . ' n\'existe pas.');
        }
        $reservation->setTableid($table);

        $entityManager->persist($reservation);
        $entityManager->flush();

        //$table->markAsOccupied();
        $entityManager->flush();
  
        return $this->redirectToRoute('frontres', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('reservation/reserverfront.html.twig', [
        'reservation' => $reservation,
        'form' => $form,
    ]);
}


*/
#[Route('/newreservation/{tableId}', name: 'app_reservation', methods: ['GET', 'POST'])]
public function reservationfront(Request $request, EntityManagerInterface $entityManager, $tableId , QrCodeGenerator $qrCodeGenerator): Response
{
$reservation = new Reservation();
$form = $this->createForm(ReservationType::class, $reservation);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
    $formData = $form->getData();
    $chosenDateTime = $formData->getDateTime();

    $existingReservation = $entityManager->getRepository(Reservation::class)->findOneBy([
        'dateTime' => $chosenDateTime,
    ]);

    if ($existingReservation) {
        $this->addFlash('error', 'This date and time is already reserved.');
        return $this->renderForm('reservation/reserverfront.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);            
    }

    $table = $entityManager->getRepository(RestaurantTable::class)->find($tableId);
    if (!$table) {
        throw $this->createNotFoundException('La table avec l\'ID ' . $tableId . ' n\'existe pas.');
    }
    $reservation->setTableid($table);

    $entityManager->persist($reservation);
    $entityManager->flush();
  $qrCodeResult = $qrCodeGenerator->createQrCode($reservation);



  return $this->renderForm('reservation/qrcode.html.twig', [
    'qrCodeResult' => $qrCodeResult,

]);
}

return $this->renderForm('reservation/reserverfront.html.twig', [
'reservation' => $reservation,
'form' => $form,
]);
}


    #[Route('/{id}/editfront', name: 'app_reservationedit', methods: ['GET', 'POST'])]
    public function editfront(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('frontresv', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/editfront.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/del', name: 'app_reservation_deletefront', methods: ['GET', 'POST'])]
    public function deletefront(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST') && $this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
          
            $entityManager->remove($reservation);
            $entityManager->flush();
            $tableid = $reservation->getTableid(); 
    
            $table = $entityManager->getRepository(RestaurantTable::class)->find($tableid);
    
            $table->markAsDisponible(); 
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('frontresv', [], Response::HTTP_SEE_OTHER);
    }
    





    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

  #[Route('/reservation/{id}', name: 'app_reservation_show', methods: ['GET'])]
public function show(Reservation $reservation, QrCodeGenerator $qrCodeGenerator): Response
{
    if ($reservation === null) {
        throw $this->createNotFoundException("Réservation non trouvée.");
    }

    $qrCodeResult = $qrCodeGenerator->createQrCode($reservation);

    return $this->render('reservation/show.html.twig', [
        'reservation' => $reservation,
        'qrCodeResult' => $qrCodeResult,
    ]);
}


    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {     
        if ($request->isMethod('POST') && $this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
          
            $entityManager->remove($reservation);
            $entityManager->flush();
            $tableid = $reservation->getTableid(); 
    
            $table = $entityManager->getRepository(RestaurantTable::class)->find($tableid);
    
            $table->markAsDisponible(); 
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/backrechercheAjax', name: 'backrechercheAjax', methods: ['GET'])]
public function searchAjax(Request $request, ReservationRepository $repo): Response
{
    $query = $request->query->get('q');

    if (empty($query)) {
        $reservations = $repo->findAll(); // Renvoyer tous les résultats
    } else {
        $reservations = $repo->findrepasByStats($query); // Recherche par critère
    }

    // Rendre le template avec les résultats de la recherche
    $html = $this->renderView("reservation/index.html.twig", [
        'reservations' => $reservations,
    ]);

    return new Response($html); // Retourne le HTML comme réponse AJAX
}
/*
#[Route('/statistics', name: 'statistics', methods: ['GET'])]
    public function statistics(): Response
    {
        $disponibleCount = $this->countGenderForEvents('disponible');
        $nondisponibleCount = $this->countGenderForEvents('nondisponible');
      
    
        $data = [
            'disponible' => $disponibleCount,
            'nondisponible' => $nondisponibleCount,
            
        ];
    
        $jsonData = json_encode($data);
    
        return $this->render('reservation/statistics.html.twig', [
            'disponible' => $disponibleCount,
            'nondisponible' => $nondisponibleCount,
           
            'jsonData' => $jsonData
        ]);
    }


*/

   
   
}

