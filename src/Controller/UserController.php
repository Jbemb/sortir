<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user-update/{id}", name="user_update")
     */
    public function userUpdate($id, EntityManagerInterface $em, Request $request)
    {
        //récupère le UserRepository
        $UserRepo = $this->getDoctrine()->getRepository(User::class);
        //Requête SQL pour récupérer les infos du user
        $user = $UserRepo->find($id);
        dump($user);
        //création de mon formulaire
        $UserUpdateForm = $this->createForm(UserUpdateType::class, $user);
        //récupère les infos inscrit dans le form
        $UserUpdateForm->handleRequest($request);
        //vérifie la validiter du formulaire
        if ($UserUpdateForm->isSubmitted()){
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Votre profil a été mise à jour");
            return $this->redirectToRoute('home');
        }

        return $this->render('user/update.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/user-profil/{id}", name="user_profil")
     */
    public function userProfil($id)
    {
        return $this->render('user/profil.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
