<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('Admin/test.html.twig', [
            'name' => 'UserController',
        ]);
    }
     #[Route('/user/ajout', name: 'app_user.Ajout')]
    public function AjouterUser(Request $request,ManagerRegistry $managerRegistry): Response
    { $user=new User();
        $em= $managerRegistry->getManager();
        $form=$this->createForm(UserType::class,$user);
        $form->remove('token');
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
               $em->persist($user);
               $em->flush();
        }

        return $this->render('Admin/User/AjouterUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }
      #[Route('/user/afficher', name: 'app_user.Afficher')]
    public function AfficherUser(UserRepository $userRepository): Response
    { $i=$userRepository->findAll();

        return $this->render('Admin/User/AfficherUser.html.twig', [
           'i' => $i 
        ]);
    }

       #[Route('/user/supprimer/{id}', name: 'app_user.Supprimer')]
    public function SupprimerUser(UserRepository $userRepository,$id,ManagerRegistry $managerRegistry): Response
    {
         $i=$userRepository->find($id);
            $em = $managerRegistry->getManager();
            $em->remove($i);
            $em->flush();
         return $this->redirectToRoute('app_user.Afficher');
    }
    
}
