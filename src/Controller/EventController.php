<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class EventController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/sortie/ajouter", name="event_add", methods={"GET", "Post"})
     *
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $user = $this->security->getUser();
        $event = new Event();
        $event->setOrganiser($user);
        $event->setCampus($user->getCampus);
        $eventForm = $this->createForm(EventType::class, $event);

        $eventForm -> handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()){

            if(published){
                $event->setState();

                $em->persist($event);
                $em->flush();

                $this->addFlash("success", "Sortie publiée");
                return $this->redirectToRoute("event_post", ["id" => $event->getId()]);
            } elseif (saved){
                $event->setState();

                $em->persist($event);
                $em->flush();

                $this->addFlash("success", "Sortie enregistréé");
                return $this->redirectToRoute("event_post", ["id" => $event->getId()]);
            } else{
                $em->persist($event);
                $em->flush();

                $this->addFlash("cancel", "Pas de sortie crée");
                return $this->redirectToRoute("home", ["id" => $event->getId()]);
            }


        }

        return $this->render('event/add.html.twig', [
            'eventForm' => $eventForm->createView()
        ]);
    }
}
