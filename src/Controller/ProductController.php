<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Product;
use App\Entity\ProductRating;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Form\ProductType;
use App\Repository\CategorieRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\PanierRepository;


class ProductController extends AbstractController
{


    #[Route('/pdfp', name: 'pdfP', methods: ['GET'])]
    public function pdf(ProductRepository $productRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve products from the repository
        $products = $productRepository->findAll();

        // Iterate over products and update image data
        foreach ($products as $product) {
            // Construct the path to the image file
            $pathToImage = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $product->getImage();

            // Check if the file exists
            if (file_exists($pathToImage)) {
                // Read and encode image data
                $imageData = base64_encode(file_get_contents($pathToImage));
                $product->setImage($imageData); // Set the image data back to the product object
            } else {
                // Handle missing image file
                // You may log an error or take appropriate action
            }
        }

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('product/pdf.html.twig', [
            'products' => $products,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF for download
        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="mypdf.pdf"',
            ]
        );
    }
    #[Route('/rating_statistics', name: 'rating_statistics')]
    public function ratingStatistics(ProductRepository $productRepository, PanierRepository $panierRepository): Response
    {
        $nombreDePaniers = $panierRepository->count([]);

        // Rendre la variable accessible globalement dans les templates Twig
        $this->get('twig')->addGlobal('nombreDePaniers', $nombreDePaniers);

        $statistics = $productRepository->calculateRatingStatistics();

        // Faites quelque chose avec les statistiques, par exemple, passez-les à votre vue Twig pour l'affichage
        return $this->render('product/ratingStatistics.html.twig', [
            'statistics' => $statistics,
        ]);
    }
    #[Route('/P', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository, PaginatorInterface $paginator, Request $request): Response
    {



        $productsQuery = $productRepository->findAll(); // Récupérer tous les produits
        $pagination = $paginator->paginate(
            $productsQuery, // La query à paginer
            $request->query->getInt('page', 1), // Récupérer le numéro de page à partir de la requête, 1 par défaut
            3 // Nombre d'éléments par page //9otlou page wahda tekhou 3 lokhra dhenya kdhet zuuz khater mafamesh theldha
        );

        return $this->render('product/index.html.twig', [
            'products' => $pagination, // Passer la pagination à la vue
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, PanierRepository $panierRepository): Response
    {
        $nombreDePaniers = $panierRepository->count([]);

        // Rendre la variable accessible globalement dans les templates Twig
        $this->get('twig')->addGlobal('nombreDePaniers', $nombreDePaniers);

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileUpload = $form->get('image')->getData();

            $fileName = md5(uniqid()) . '.' . $fileUpload->guessExtension();

            $fileUpload->move($this->getParameter('kernel.project_dir') . '/public/uploads', $fileName); // Creation dossier uploads

            $product->setImage($fileName);
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash( //heya mohtama b notification
                'success',
                'Added successfully!'
            );

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /*
 
    #[Route('/product/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(ProductRepository $productRepository, Product $product, ProductRating $productRating, int $id): Response
    {
        $rating = $this->getDoctrine()->getRepository(productRating::class)->findBy(['id' => $product]);

        $product = $productRepository->find($id);
        
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
    
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
    */


    #[Route('/product/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(PanierRepository $panierRepository, ProductRepository $productRepository, Product $product, ProductRating $productRating, int $id): Response
    {
        $nombreDePaniers = $panierRepository->count([]);

        // Rendre la variable accessible globalement dans les templates Twig
        $this->get('twig')->addGlobal('nombreDePaniers', $nombreDePaniers);

        // Récupérer le produit
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        // Récupérer les évaluations pour ce produit
        $ratings = $product->getProductRatings();

        // Calculer la moyenne des évaluations
        $totalRatings = count($ratings);
        $sumRatings = 0;
        foreach ($ratings as $rating) {
            $sumRatings += $rating->getNbrratting();
        }
        $averageRating = $totalRatings > 0 ? $sumRatings / $totalRatings : 0;

        return $this->render('product/showfront.html.twig', [
            'product' => $product,
            'averageRating' => $averageRating,
        ]);
    }

    #[Route('/productt/{id}', name: 'app_product_showw', methods: ['GET'])]
    public function showw(ProductRepository $productRepository, Product $product, PanierRepository $panierRepository, ProductRating $productRating, int $id): Response
    {
        $nombreDePaniers = $panierRepository->count([]);

        // Rendre la variable accessible globalement dans les templates Twig
        $this->get('twig')->addGlobal('nombreDePaniers', $nombreDePaniers);

        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
        // Récupérer les évaluations pour ce produit
        $ratings = $product->getProductRatings();

        // Calculer la moyenne des évaluations
        $totalRatings = count($ratings);
        $sumRatings = 0;
        foreach ($ratings as $rating) {
            $sumRatings += $rating->getNbrratting();
        }
        $averageRating = $totalRatings > 0 ? $sumRatings / $totalRatings : 0;

        return $this->render('product/showfront.html.twig', [
            'product' => $product,
            'averageRating' => $averageRating,

        ]);
    }



    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager, PanierRepository $panierRepository): Response
    {
        $nombreDePaniers = $panierRepository->count([]);

        // Rendre la variable accessible globalement dans les templates Twig
        $this->get('twig')->addGlobal('nombreDePaniers', $nombreDePaniers);

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileUpload = $form->get('image')->getData();

            $fileName = md5(uniqid()) . '.' . $fileUpload->guessExtension();

            $fileUpload->move($this->getParameter('kernel.project_dir') . '/public/uploads', $fileName); // Creation dossier uploads

            $product->setImage($fileName);
            $entityManager->flush();


            $this->addFlash(
                'info',
                'Updated successfully!'
            );

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager, PanierRepository $panierRepository): Response
    {
        $nombreDePaniers = $panierRepository->count([]);

        // Rendre la variable accessible globalement dans les templates Twig
        $this->get('twig')->addGlobal('nombreDePaniers', $nombreDePaniers);

        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash(
                'del',
                'deleted successfully!'
            );
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }


    /*  #[Route('/front', name: 'app_categorie_front')]
    public function productsPage(CategorieRepository $categorieRepository): Response
{
    $categories = $categorieRepository->findAllWithProducts();
    return $this->render('product/productFront.html.twig', [
        'categories' => $categories,
    ]);
}*/





















    #[Route('/prodfront', name: 'app_product_front')]
    public function indexx(Request $request, ProductRepository $productRepository, PanierRepository $panierRepository, CategorieRepository $categorieRepository): Response
    {

        $nombreDePaniers = $panierRepository->count([]);

        // Rendre la variable accessible globalement dans les templates Twig
        $this->get('twig')->addGlobal('nombreDePaniers', $nombreDePaniers);
        $categorieId = $request->query->get('categorie');

        if ($categorieId) {
            $products = $productRepository->findBy(['idCategorie' => $categorieId]);
        } else {
            $products = $productRepository->findAll();
        }

        $categories = $categorieRepository->findAll();

        return $this->render('product/productFront.html.twig', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }










    #[Route('/front', name: 'front', methods: ['GET'])]
    public function front(ProductRepository $productRepository, PanierRepository $panierRepository): Response
    {


        $nombreDePaniers = $panierRepository->count([]);

        // Rendre la variable accessible globalement dans les templates Twig
        $this->get('twig')->addGlobal('nombreDePaniers', $nombreDePaniers);
        return $this->render('baseFront.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }








    #[Route('/front/filter-products', name: 'app_product_filter', methods: ['POST'])]
    public function filterProducts(Request $request, ProductRepository $productRepository, PanierRepository $panierRepository): JsonResponse
    {
        $nombreDePaniers = $panierRepository->count([]);

        // Rendre la variable accessible globalement dans les templates Twig
        $this->get('twig')->addGlobal('nombreDePaniers', $nombreDePaniers);
        // Récupérer l'ID de la catégorie sélectionnée depuis la requête AJAX
        $categoryId = $request->request->get('categoryId'); // Utiliser 'categoryId' au lieu de 'idCategorie'

        // Récupérer les produits associés à la catégorie sélectionnée
        $products = $productRepository->findByCategoryId($categoryId);

        // Créer un tableau des données des produits
        $productData = [];
        foreach ($products as $product) {
            $productData[] = [
                'id' => $product->getId(),
                'name' => $product->getName(), // Modifier pour correspondre à votre nom de champ dans l'entité Product
                'image' => $product->getImage(), // Modifier pour correspondre à votre nom de champ dans l'entité Product
                'price' => $product->getPrice(), // Modifier pour correspondre à votre nom de champ dans l'entité Product
            ];
        }

        // Retourner les données des produits au format JSON
        return new JsonResponse($productData);
    }
}
