<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\CancelEventType;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
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
     * @Route("/sortie/cancel/{id}", name="event_cancel" methods={"GET", "POST"})
     */
    public function cancel($id, EventRepository $repo, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Event::class);
        $event = $repo->find($id);

        $cancelEventForm = $this->createForm(CancelEventType::class);
      //  $cancelEventForm->handleRequest($request);


        $cancelEventFormView = $cancelEventForm->createView();



        return $this->render('event/cancel.html.twig', compact('cancelEventFormView','event'));
    }

}



