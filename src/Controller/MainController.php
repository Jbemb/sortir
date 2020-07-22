<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Search;
use App\Entity\State;
use App\Form\SearchType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, EventRepository $eventRepository, Security $security, SessionInterface $session)
    {
        $search = new Search();
        // add default options maybe in SearchType::class
        $search->setCampus($this->getUser()->getCampus());


        // Get old search from session to always show previous search if user come back from elsewhere
        if (!is_null($session->get('search'))) {
            $search->unserialize($session->get('search'));
            // TODO find another way to manage it to get rid of deprecated function
            $search->setCampus($this->getDoctrine()->getManager()->merge($search->getCampus()));
        }

        $searchForm = $this->createForm(SearchType::class, $search);

        $searchForm->handleRequest($request);

        $events = [];

        if ($searchForm->isSubmitted()) {
            $session->set('search', $search->serialize());
        }

        $events = $eventRepository->search($search, $security->getUser());

        return $this->render('main/search.html.twig', [
            'searchForm' => $searchForm->createView(),
            'events' => $events
        ]);
    }
}
