<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserUpdateType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
     * @Route("/profil", name="user_update")
     */
    public function userUpdate(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->security->getUser();
        $userUpdateForm = $this->createForm(UserUpdateType::class, $user);
        //récupère les infos inscrit dans le form
        $userUpdateForm->handleRequest($request);
        //vérifie la validiter du formulaire
        if ($userUpdateForm->isSubmitted() && $userUpdateForm->isValid()) {
            //encoder le password modifier ou pas
            $password = $user->getPassword();
            $encoded = $encoder->encodePassword($user, $password);
            $user->setPassword($encoded);

            $photo = $user->getPhoto();

            if ($photo) {
                $safeFilename = uniqid();
                $newFilename = $safeFilename . "." . $photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setPhotoName($newFilename);
            }
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Votre profil a été mise à jour.");
            return $this->redirectToRoute('home');

        }

        return $this->render('user/update.html.twig', ['controller_name' => 'UserController',
            'user' => $user,
            'UserForm' => $userUpdateForm->createView(),]);
    }





/**
 * @Route("/user/{id}", name="user_profil")
 */
public
function userProfil($id, UserRepository $userRepository)
{
//        $user = $userRepository->findUserByIdWithCampus($id);
    $user = $userRepository->find($id);

    if (!$user) {
        throw $this->createNotFoundException(
            'Utilisateur inconnu'
        );
    }
    return $this->render('user/profil.html.twig', [
        'user' => $user
    ]);
}
}
