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
        $cityId = $request->query->get('cityId');

        //find city object in bdd
        $city =$cityRepo->find($cityId);
        //get array of places based on the city
        $places = $placeRepo->findBy(
            ['city' => $city],
            ['name' => 'ASC']
        );

        //send response
        $response = new JsonResponse($places);
        return $response;
        //return $this->render('place/index.html.twig', [
        //    'results'        => $places
        //]);
    }
}
