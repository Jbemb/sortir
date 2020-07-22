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
        // TODO add default options maybe in SearchType::class
//        $search->setIsOrganiser(true);
//        $search->setIsSignedUp(true);
//        $search->setIsNotSignedUp(true);
        $search->setCampus($this->getUser()->getCampus());


        // TODO Get old search from session to always show previous search if user come back from elsewhere

        dump($session->get('search'));
        if (!is_null($session->get('search'))) {
            $search->unserialize($session->get('search'));
            $search->setCampus($this->getDoctrine()->getManager()->merge($search->getCampus()));
        }
        dump($search);

        $searchForm = $this->createForm(SearchType::class, $search);

        $searchForm->handleRequest($request);

        // TODO see how to keep search object in session
        /*$userSearch = $request->request->get('search');

        unset($userSearch["submit"]);
        unset($userSearch["_token"]);
        dump($userSearch);
        $session->set('userSearch', $userSearch);

//        $normalizer = new ObjectNormalizer(null, null, null, new ReflectionExtractor());
//        $serializer = new Serializer([new DateTimeNormalizer(), $normalizer]);
//        $userSearchObject2 = $serializer->denormalize($userSearch, Search::class, '[]');
//        dump($userSearchObject2);

        //Retourne la représentation JSON d'une valeur https://github.com/pmill/doctrine-array-hydrator
//        $userSearchJson = json_encode($userSearch);
//        dump($userSearchJson);

//        $userSearch1 = new Search();
//        $userSearch1->setKeywords($userSearch["keywords"]);
//        $userSearch1->setStartDate($userSearch["startDate"]);
//        $userSearch1->setEndDate($userSearch["endDate"]);
//        dump($userSearch1);
//
//        $userSearchObject = $this->get('serializer')->deserialize($userSearchJson, Search::class, 'json');
//        dump($userSearchObject);
*/
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
