<?php

namespace App\Controller;

use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/sortie/ajouter", name="event_add")
     */
    public function add()
    {
        $eventForm = $this->createForm(EventType::class);

        return $this->render('event/add.html.twig', [
            'eventForm' => $eventForm->createView()
        ]);
    }
    
    /**
     * @Route("/sortie/{id}/inscription",
     *     name="event_signup",
     *     requirements={"id"="\d+"}
     *     )
     */
    public function signUp($id, EventRepository $eventRepository, EntityManagerInterface $em, UserRepository $userRepository)
    {
        $user = $userRepository->findOneBy(['username' => $this->security->getUser()->getUsername()]) ;
        $event = $eventRepository->find($id);

        $event->addParticipant($user);

        $em->persist($event);
        $em->flush();

        $this->addFlash('success', 'Vous vous êtes inscrit à la sortie : ' . $event->getName());

        // TODO redirect vers page d'affichage
    }

    /**
     * @Route("/sortie/{id}",
     *     name="event_show",
     *     requirements={"id"="\d+"}
     *     )
     */
    public function show($id, EventRepository $eventRepository)
    {
        $event = $eventRepository->find($id);

        return $this->render('event/show.html.twig', [
            "event" => $event
        ]);
    }
}
