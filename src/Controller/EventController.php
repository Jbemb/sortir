<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\CancelEventType;
use App\Entity\State;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\StateRepository;
use App\Repository\UserRepository;
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
    public function add(Request $request, EntityManagerInterface $em, UserRepository $userRepo, StateRepository $stateRepo)
    {
        $user = $userRepo->findOneBy(['username' => $this->security->getUser()->getUsername()]);
        $event = new Event();
        $event->setOrganiser($user);
        $event->setCampus($user->getCampus());
        $eventForm = $this->createForm(EventType::class, $event);

        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()){
            $state= new State;

            if ($eventForm->get('saveAndAdd')->isClicked()) {
                $state = $stateRepo->findOneBy(['name' => 'Ouverte']);

            }elseif ($eventForm->get('save')->isClicked()){
                $state = $stateRepo->findOneBy(['name' => 'Créée']);
            }
            $event->setState($state);
            $em->persist($event);
            $em->flush();

            $this->addFlash("success", "Sortie enregistréé");
            return $this->redirectToRoute("event_post", ["id" => $event->getId()]);

        }

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

    /**
     * @Route("/sortie/cancel/{id}", name="event_cancel", methods={"GET", "POST"})
     */
    public function cancel($id, EventRepository $repo, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Event::class);
        $event = $repo->find($id);

        $cancelEventForm = $this->createForm(CancelEventType::class);
        //  $cancelEventForm->handleRequest($request);


        $cancelEventFormView = $cancelEventForm->createView();


        return $this->render('event/cancel.html.twig', compact('cancelEventFormView', 'event'));
    }
}