<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SearchUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\UploderService;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user'),IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('Admin/test.html.twig', [
            'name' => 'UserController',
        ]);
    }

    public function __construct(private ManagerRegistry $managerRegistry,
                                private UserRepository  $userRepository,
                                private UploderService  $uploderService
    )
    {

    }

    #[Route('/user/ajout', name: 'app_user.Ajout')]
    public function AjouterUser(
        Request                     $request,
        UserPasswordHasherInterface $userPasswordHasher

    ): Response
    {

        $user = new User();
        $em = $this->managerRegistry->getManager();
        $form = $this->createForm(UserType::class, $user);
        $form->remove('token');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('image')->getData();
            $password = $form->get('password')->getData();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $password
                ));
            if ($picture) {
                $newFileName = $this->uploderService->uploadFile($picture, $this->getParameter('user_directory'));
                $user->setImage($newFileName);
            }
            $user->setActive(true);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success',"user ajouté avec succe");
            return $this->redirectToRoute("app_user.Afficher");
        }

        return $this->render('Admin/User/AjouterUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/afficher', name: 'app_user.Afficher')]
    public function AfficherUser(UserRepository $userRepository,Request $request): Response
    {
        $i = $userRepository->findAll();
          $form = $this->createForm(SearchUserType::class);
           $search = $form->handleRequest($request);
           if($form->isSubmitted() && $form->isValid()){
               $i=$userRepository->search($search->get('mots')->getData());
           }
        return $this->render('Admin/User/AfficherUser.html.twig', [
            'i' => $i,
            'form'  => $form->createView()
        ]);

    }

    #[Route('/user/supprimer/{id}', name: 'app_user.Supprimer')]
    public function SupprimerUser(UserRepository $userRepository, $id, ManagerRegistry $managerRegistry): Response
    {
        $i = $userRepository->find($id);
        $picture = $this->getParameter('user_directory') . '/' . $i->getImage();
        if (file_exists($picture)) {

            $filesystem = new Filesystem();
            $filesystem->remove($picture);
        }
        $em = $managerRegistry->getManager();
        $em->remove($i);
        $em->flush();
        $this->addFlash('success',"user supprimer avec succe");
        return $this->redirectToRoute('app_user.Afficher');
    }


    #[Route('/user/modifier/{id}', name: 'app_user.Modifier')]
    public function modifierUser(
        $id,
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher
    )
    {
        $user = $this->userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->remove('token');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $form->isRequired()) {
            $em = $this->managerRegistry->getManager();
            $user->setActive(true);
            $picture = $form->get('image')->getData();
            $password = $form->get('password')->getData();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $password
                ));
            if ($picture) {
                $newFileName = $this->uploderService->uploadFile($picture, $this->getParameter('user_directory'));
                $user->setImage($newFileName);
            }
            $em->persist($user);
            $em->flush();
            $this->addFlash('success',"user modifier avec succe");
            return $this->redirectToRoute('app_user.Afficher');
        }
        return $this->render('Admin/User/ModifierUser.html.twig', [
            'form' => $form->createView()

        ]);


    }

    #[Route('/user/activer/{id}', name: 'user.active')]
    public function activerUser(User $user =null,ManagerRegistry $managerRegistry)
    {

        $user->setActive(($user->isActive())? false : true);
        $em = $managerRegistry->getManager();
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('app_user.Afficher');
    }
    #[Route('/user/details/{id}', name: 'user.details')]
    public function UserDetails(User $user =null,UserRepository $repository)
    {


        return $this->render('Admin/User/showUserDetails.html.twig', [
            'user' => $user
        ]);
    }

}

