<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\CancelEventType;
use App\Entity\State;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\StateRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
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
        $event->setInscriptionLimit(new \DateTime());
        $event->setStartDateTime(new \DateTime());
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

    /**
     * @Route("/sortie/cancel/{id}", name="event_cancel", methods={"GET", "POST"})
     */
    public function cancel($id, EventRepository $repo, Request $request, EntityManagerInterface $em, StateRepository $stateRepo)
    {
        //recup les infos de l event pour pouvoir les afficher dans le twig
        $repo = $this->getDoctrine()->getRepository(Event::class);
        $event = $repo->find($id);

        $cancelEventForm = $this->createForm(CancelEventType::class, $event);
        //recupere les infos du form
        $cancelEventForm->handleRequest($request);

        if ($cancelEventForm->isSubmitted() && $cancelEventForm->isValid()){

            $stateRepo= $this->getDoctrine()->getRepository(State::class);
            $state = $stateRepo->findOneBy(array('name'=>'annulée'));

            $event->setState($state);// doit etre plus complexe que ca

            $em->persist($event);
            $em->flush();
            return  $this->redirectToRoute('home');
        }

        $cancelEventFormView = $cancelEventForm->createView();


        return $this->render('event/cancel.html.twig', compact('cancelEventFormView', 'event'));
    }

    /**
     * @Route("/sortie/sedesister/{id}", name="event_withdraw")
     */
    public function withdraw($id, EventRepository $repo, UserRepository $userRepo, EntityManager $em, StateRepository $stateRepo)
    {
        //get event
        $repo = $this->getDoctrine()->getRepository(Event::class);
        $event = $repo->find($id);

        //get user
        $user = $userRepo->findOneBy(['username' => $this->security->getUser()->getUsername()]);

        //update event participants
        $event->removeParticipant($user);
        //update state
        $state = $stateRepo->findOneBy(['name' => 'Ouverte']);
        $event->setState($state);

        //update database
        $em -> persist($event);
        $em -> flush($event);

        $this->addFlash("success", "Vous vous êtes désinscrit(e)");
        return $this->render('main/index.html.twig');
    }
}