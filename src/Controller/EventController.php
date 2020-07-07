<?php

namespace App\Controller;

use App\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
