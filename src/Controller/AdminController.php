<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminCreateUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
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
    public function createUser(Request $request, EntityManagerInterface $em)
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
            $em->persist($newUser);
            $em->flush();

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
