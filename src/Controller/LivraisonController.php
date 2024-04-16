<?php

namespace App\Controller;
use Doctrine\Common\Collections\ArrayCollection;
use Dompdf\Dompdf;

use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Repository\CommandeRepository;
use App\Repository\LivraisonRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
#[Route('/livraison')]



class LivraisonController extends AbstractController
{

    //composer require php-flasher/flasher-symfony
//composer require phpoffice/phpspreadsheet
//composer require ultramsg/whatsapp-php-sdk
#[Route('/generateExcel', name: 'excel')]
public function generateUserExcel(LivraisonRepository $liv,UserRepository $us,CommandeRepository
 $cmd): BinaryFileResponse
{
    $livraisons = $liv->findAll();
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Define column names
    $columnNames = ['adresse Depart', 'adresse Arrive', 'Etat',];

    // Set the entire first row at once and make it bold
    $sheet->fromArray([$columnNames], null, 'A1');
    $sheet->getStyle('A1:G1')->getFont()->setBold(true);

    $sn = 2; // Start from the second row
    foreach ($livraisons as $l) {
        $data = [
            $l->getAdresseDepart(),
            $l->getAdresseArrive(),
            $l->getEtat(),
            ];

        // Set data starting from the second row
        $sheet->fromArray([$data], null, 'A' . $sn);

        $sn++;
    }
    $sheet->getStyle('A1:D1')->applyFromArray([
        'font' => [
            'bold' => true,
        ],
    ]);
    $writer = new Xlsx($spreadsheet);

    $fileName = 'livraisons.xlsx';
    $tempFile = tempnam(sys_get_temp_dir(), $fileName);

    $writer->save($tempFile);

    return new BinaryFileResponse($tempFile, 200, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => sprintf('inline; filename="%s"', $fileName),
    ]);
}
    #[Route('/', name: 'app_livraison_index', methods: ['GET'])]
    public function index(LivraisonRepository $livraisonRepository): Response
    {
        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisonRepository->findAll(),
        ]);
    }
    #[Route('/f', name: 'app_livraison_indexf', methods: ['GET'])]
    public function indexf(LivraisonRepository $livraisonRepository): Response
    {
        return $this->render('livraison/indexf.html.twig', [
            'livraisons' => $livraisonRepository->findAll(),
        ]);
    }
    //composer require dompdf/dompdf
    #[Route('/generate-pdf-cmds', name: 'generate_pdf_livraisons')]
    public function generatePdfCommande(CommandeRepository $cmd): Response
    {
        // Récupérez toutes les livraisons depuis le repository
        $cmds = $cmd->findAll();
    
        // Récupérez le contenu du template Twig
        $html = $this->renderView('livraison/pdf.html.twig', [
            // Passez les données nécessaires au template Twig
            'cmds' => $cmds,
        ]);
    
        // Instanciez Dompdf avec les options de votre choix
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
    
        // Définissez les options du PDF si nécessaire
        $dompdf->setPaper('A4', 'portrait');
    
        // Générez le PDF
        $dompdf->render();
    
        // Renvoyez le PDF en tant que réponse
        return new Response($dompdf->output(), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
        ]);
    }
    

    #[Route('/new', name: 'app_livraison_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les commandes sélectionnées dans le formulaire
            $selectedCommandes = $form->get('commandes')->getData();
    
            // Associer chaque commande à la livraison
            foreach ($selectedCommandes as $commande) {
                $livraison->addCommande($commande);
                $commande->setLivraison($livraison); // Mettre à jour la livraison de chaque commande
                $entityManager->persist($commande); // Persistez chaque commande
            }
    
            // Enregistrer la livraison dans la base de données
            $entityManager->persist($livraison);
            $entityManager->flush();
    
            $this->addFlash('success', 'Le livraison a été ajouté avec succès.');
    
            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('livraison/new.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_livraison_show', methods: ['GET'])]
    public function show(Livraison $livraison): Response
    {
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraison,
        ]);
    }
  

    
    #[Route('/{id}/edit', name: 'app_livraison_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $newCommandes = $form->get('commandes')->getData();
    
            foreach ($livraison->getCommandes() as $commande) {
                $commande->setLivraison(null);

            }
    
            foreach ($newCommandes as $commande) {
                $commande->setLivraison($livraison);

            }
    
            $entityManager->flush();
    
            $this->addFlash('success', 'Le livraison a été modifié avec succès.');
    
            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('livraison/edit.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_livraison_delete', methods: ['POST'])]
    public function delete(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livraison->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livraison);
            $entityManager->flush();
            $this->addFlash('success', 'Le livraison a été supprimé avec succès.');

        }

        return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
    }


}
