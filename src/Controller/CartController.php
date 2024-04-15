<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $entityManager;
    private $productRepository;

    public function __construct(EntityManagerInterface $entityManager, ProductRepository $productRepository)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
    }

    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        // Retrieve the Panier entity and render the cart page
        $panier = $this->entityManager->getRepository(Panier::class)->findAll();
    
        return $this->render('cart/index.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/add-to-cart', name: 'app_add_to_cart', methods: ['POST'])]
    public function addToCart(Request $request): Response
    {
        $productId = $request->request->get('product_id');
        $product = $this->productRepository->find($productId);

        if (!$product) {
            throw $this->createNotFoundException('Product not found.');
        }

        // naamlou panier jdid
        $panier = new Panier();
        // hethy bch nhotou Quantity 1 par defaut just l test le tempt li nzidha b list 
        $panier->setQuantity(1);
        //hnee bch  nekhou prix ta3 produit w nzidou f panier 
        $panier->setPrixTotal((float) $product->getPrice());
        // hnee bch nzid id  produit ll panier 
        $panier->addProductId($product);

        // Persist bch nbadel base  w nstoki 
        $this->entityManager->persist($panier);
        $this->entityManager->flush();

        // Redirect back to the cart page
        return $this->redirectToRoute('app_cart');
    }

#[Route('/remove-from-cart/{id}', name: 'app_remove_from_cart', methods: ['POST'])]
public function removeFromCart(int $id): Response
{
    $panier = $this->entityManager->getRepository(Panier::class)->find($id);

    if (!$panier) {
        throw $this->createNotFoundException('No cart item found for id '.$id);
    }

    $this->entityManager->remove($panier);
    $this->entityManager->flush();

    $this->addFlash('success', 'Product removed from cart successfully');

    return $this->redirectToRoute('app_cart');
}



#[Route('/update-cart-quantity', name: 'update_cart_quantity', methods: ['POST'])]
public function updateCartQuantity(Request $request): Response
{
    $data = json_decode($request->getContent(), true);
    $productId = $data['productId'];
    $quantity = $data['quantity'];

    $product = $this->productRepository->find($productId);
    $panier = $this->entityManager->getRepository(Panier::class)->findOneBy(['product' => $product]);

    if (!$product || !$panier) {
        return $this->json(['status' => 'error', 'message' => 'Product or cart item not found.'], 404);
    }

    $panier->setQuantity($quantity);
    $panier->setPrixTotal($product->getPrice() * $quantity);
    $this->entityManager->flush();

    return $this->json(['status' => 'success', 'message' => 'Cart updated successfully.']);
}

#[Route('/validate-cart', name: 'app_validate_cart', methods: ['POST'])]
public function validateCart(): Response
{
    $panierItems = $this->entityManager->getRepository(Panier::class)->findAll();

    if (!$panierItems) {
        $this->addFlash('error', 'Le panier est déjà vide.');
        return $this->redirectToRoute('app_product_front');
    }

    foreach ($panierItems as $item) {
        $this->entityManager->remove($item);
    }
    $this->entityManager->flush();

    $this->addFlash('success', 'Votre panier a été validé avec succès!');
    return $this->redirectToRoute('app_product_front'); // Redirection vers la page du panier ou une page de confirmation
}



    
}
