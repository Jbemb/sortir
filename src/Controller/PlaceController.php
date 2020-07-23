<?php

namespace App\Controller;

use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends AbstractController
{
    /**
     * @Route("/placebycity", name="place_find_by_city", methods={"POST"})
     */
    public function findPlaceByCity(Request $request, CityRepository $cityRepo, PlaceRepository $placeRepo)
    {
        //get id from http request
        $cityId = $request->request->get('cityId');

        //find city object in bdd
        $city = $cityRepo->find($cityId);

        //use method from repo to get places
        $places = $placeRepo->findPlacesByCity($city);

        $response = new JsonResponse($places);
        return $response;
        /*
        return $this->render('place/createUser.html.twig', [
           'results'        => $places
        ]);
        */
    }

    /**
     * @Route("/streetplace", name="street_of_the_place", methods={"POST"})
     */
    public function findStreetPlace(Request $request, PlaceRepository $placeRepo){
        $placeId = $request->request->get('placeId');

        $place = $placeRepo->findPlaceById($placeId);

        $response = new JsonResponse($place);
        return $response;
    }
}
