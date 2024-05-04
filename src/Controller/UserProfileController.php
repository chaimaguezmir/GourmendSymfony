<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserModifierPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\UploderService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{


    public function __construct(private ManagerRegistry $managerRegistry,
                                private UserRepository  $userRepository,
                                private UploderService  $uploderService
    )
    {

    }


    #[Route('/user/profile', name: 'app_user_profile')]
    public function index(): Response
    {
        return $this->render('user_profile/index.html.twig', [
            'controller_name' => 'UserProfileController',
        ]);
    }
    #[Route('/user/profile/detail/{id}', name: 'user.details1')]
    public function userDetails(User $user = null): Response
    {

        return $this->render('User/userProfileDetails.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/user/profile/modifier/{id}', name: 'user.modifierProfile')]
    public function userModifier(
        $id,
        Request $request


    ): Response
    {
        $user = $this->userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->remove('token');
        $form->remove('password');
        $form->remove('roles');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $em = $this->managerRegistry->getManager();
            $picture = $form->get('image')->getData();
//            $user->setRoles('["ROLE_USER"]');
            $user->setPassword($user->getPassword());
            $user->setActive(true);
            if ($picture) {
                $newFileName = $this->uploderService->uploadFile($picture, $this->getParameter('user_directory'));
                $user->setImage($newFileName);
            }
            $em->persist($user);
            $em->flush();
            $this->addFlash('success',"user modifier avec succe");
            return $this->redirectToRoute('app_test');
        }
        return $this->render('User/userModifierProfile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/profile/modifierPassword/{id}', name: 'user.modifierPassword')]
    public function userModifierPassword(
        $id,
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher


    ): Response
    {
        $em =$this->managerRegistry->getManager();
        $user = $this->userRepository->find($id);
        $form = $this->createForm(UserModifierPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {

            $user->setPassword($user->getPassword());
            $password = $form->get('plainPassword')->getData();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $password
                ));

            $em->persist($user);
            $em->flush();
            $this->addFlash('success',"passsword modifier avec succee");
            return $this->redirectToRoute('app_test');
        }
        return $this->render('User/userPasswordModifier.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
