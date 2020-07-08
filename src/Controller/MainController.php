<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\State;
use App\Repository\EventRepository;
use App\Repository\StateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Event\EventChangeState;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EventChangeState $eventChangeState)
    {
        $events = $eventChangeState->changeState();
        dump($events);
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
