<?php

namespace App\Controller;

use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends AbstractController
{
    /**
     * @Route("/placebycity/{id}", name="place_find_by_city")
     */
    public function findPlaceByCity($id, CityRepository $cityRepo, PlaceRepository $placeRepo)
    {
        $city =$cityRepo->find($id);

        $places = $placeRepo->findBy(
            ['city' => $city],
            ['name' => 'ASC']
        );


        $response = new JsonResponse($places);
        return $response;
        //return $this->render('place/index.html.twig', [
        //    'results'        => $places
        //]);
    }
}
