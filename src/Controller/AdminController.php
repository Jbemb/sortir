<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminCreateUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class AdminController extends AbstractController
{

//    private $security;
//    /**
//     * Constructeur pour récupérer le user
//     */
//    public function __construct(Security $security)
//    {
//        $this->security = $security;
//    }
    /**
     * @Route("/admin/createUser", name="create_user")
     */
    public function createUser(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
//  geré  dans security.yaml->c est mieux si plusieurs pages admin
//        if(!$this->isGranted("ROLE_ADMIN")){
//            throw $this->createAccessDeniedException("Vous n'êtes pas autorisé à acceder à cette page!");
//
//        }
        $newUser = new User();
        $newUser->setIsActive(true);
        $newUser->setRoles(['ROLE_USER']);
        $newUserForm = $this->createForm(AdminCreateUserType::class, $newUser);

        $newUserForm->handleRequest($request);

        if ($newUserForm->isSubmitted() && $newUserForm->isValid()) {
            //encoder le password modifier ou pas
            $password = $newUser->getPassword();
            $encoded = $encoder->encodePassword($newUser, $password);
            $newUser->setPassword($encoded);

            $em->persist($newUser);
            $em->flush();
            $this->addFlash("success", "L'utilisateur est ajouté.");
            return $this->redirectToRoute(
                'home'
            );
        }

        return $this->render('admin/createUser.html.twig',  [
            'newUserForm' => $newUserForm->createView()
           //  'newUser'=> $newUser
        ]);
    }
}
