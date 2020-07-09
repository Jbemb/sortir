<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\State;
use App\Event\EventChangeState;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
