<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Product;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\HttpFoundation\JsonResponse;


class ProductController extends AbstractController
{
    #[Route('/P', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileUpload = $form->get('image')->getData();

            $fileName = md5(uniqid()) . '.' . $fileUpload->guessExtension();

            $fileUpload->move($this->getParameter('kernel.project_dir') . '/public/uploads', $fileName);// Creation dossier uploads

            $product->setImage($fileName);
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }


 
    #[Route('/product/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(ProductRepository $productRepository, int $id): Response
    {
        $product = $productRepository->find($id);
        
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
    
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
    
    
    
    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileUpload = $form->get('image')->getData();

            $fileName = md5(uniqid()) . '.' . $fileUpload->guessExtension();

            $fileUpload->move($this->getParameter('kernel.project_dir') . '/public/uploads', $fileName);// Creation dossier uploads

            $product->setImage($fileName);
            $entityManager->flush();
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
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
public function indexx(Request $request, ProductRepository $productRepository, CategorieRepository $categorieRepository): Response
{
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



#[Route('/productt/{id}', name: 'app_product_showw', methods: ['GET'])]
public function showw(ProductRepository $productRepository, int $id): Response
{
    $product = $productRepository->find($id);
    
    if (!$product) {
        throw $this->createNotFoundException('Product not found');
    }

    return $this->render('product/showfront.html.twig', [
        'product' => $product,
    ]);
}








#[Route('/front', name: 'front', methods: ['GET'])]
public function front(ProductRepository $productRepository): Response
{
    return $this->render('baseFront.html.twig', [
        'products' => $productRepository->findAll(),
    ]);
}








#[Route('/front/filter-products', name: 'app_product_filter', methods: ['POST'])]
public function filterProducts(Request $request, ProductRepository $productRepository): JsonResponse
{
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