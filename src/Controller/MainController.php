<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Search;
use App\Entity\State;
use App\Form\SearchType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, EventRepository $eventRepository, Security $security)
    {
        $search = new Search();
        // TODO add default options maybe in SearchType::class
        $search->setIsOrganiser(true);
        $search->setIsSignedUp(true);
        $search->setIsNotSignedUp(true);
        $search->setCampus($this->getUser()->getCampus());


        // TODO Get old search from session to always show previous search if user come back from elsewhere


        $searchForm = $this->createForm(SearchType::class, $search);

        $searchForm->handleRequest($request);

        // TODO see how to keep search object in session

        $events = [];
        if ($searchForm->isSubmitted())
        {
        }
            $events = $eventRepository->search($search, $security->getUser());

        return $this->render('main/search.html.twig', [
            'searchForm'    =>  $searchForm->createView(),
            'events'        => $events
        ]);
    }
}
