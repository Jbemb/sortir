<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{

    private $security;
    /**
     * Constructeur pour récupérer le user
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/user-update/{id}", name="user_update")
     */
    public function userUpdate(EntityManagerInterface $em, Request $request)
    {
        //récupère le UserRepository
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        //Requête SQL pour récupérer les infos du user
        $user = new User();
        $user = $this->security->getUser();
        dump($user);
        $userUpdateForm = $this->createForm(UserUpdateType::class, $user);
        //récupère les infos inscrit dans le form
        $userUpdateForm->handleRequest($request);
        //vérifie la validiter du formulaire
        if ($userUpdateForm->isSubmitted()){
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Votre profil a été mise à jour.");
            return $this->redirectToRoute('home');
        }

        return $this->render('user/update.html.twig', [
            'controller_name' => 'UserController',
            'user'=>$user,
            'UserForm'=>$userUpdateForm->createView(),
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
