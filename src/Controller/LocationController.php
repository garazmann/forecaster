<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/location-dummy')]
class LocationController extends AbstractController
{
    #[Route('/create', name: 'app_location_create')]
    public function create(LocationRepository $locationRepository): JsonResponse
    {
        $location = new Location();
        $location
            ->setName('Budějovice')
            ->setCountryCode('CZ')
            ->setLatitude(48.975658)
            ->setLongitude(14.480255)
            ;

            $locationRepository->save($location, true);

            return new JsonResponse([
                'id' => $location->getId()
            ]);
    }

    #[Route('/edit', name: 'app_location_edit')]
    public function edit(
        LocationRepository $locationRepository,
    ): JsonResponse
    {
        $location = $locationRepository->find(4);
        $location->setName('Budweiss');

        $locationRepository->save($location, true);

        return new JsonResponse([
            'id' => $location->getId(),
            'name' => $location->getName(),
        ]);
    }

    #[Route('/remove/{id}', name: 'app_location_remove')]
    public function remove(
        LocationRepository $locationRepository,
        int $id
        ): JsonResponse
    {
        $location = $locationRepository->find($id);

        $locationRepository->remove($location, true);

        return new JsonResponse(null);

    }

    #[Route('/show/{location_name}', name: 'app_location_show')]
    public function show(
        #[MapEntity(mapping: ['location_name' => 'name'])]
        Location $location,
    ): JsonResponse
    {

        $json= [
            'id' => $location->getId(),
            'name' => $location->getName(),
            'country' => $location->getCountryCode(),
            'lat' => $location->getLatitude(),
            'long' => $location->getLongitude(),
            ];

            foreach ($location->getForecasts() as $forecast) {
                $json['forecasts'][$forecast->getDate()->format('Y-m-d')] = [
                    'celsius' => $forecast->getCelsius(),
                ];
            }

            return new JsonResponse($json);
    }

    #[Route('/', name: 'app_location_index')]
    public function index(
        LocationRepository $locationRepository,
    ): JsonResponse{
        $locations = $locationRepository->findAll();

        $json = [];

        foreach ($locations as $location) {
            $json[] = [
                'id' => $location->getId(),
                'name' => $location->getName(),
                'country' => $location->getCountryCode(),
                'lat' => $location->getLatitude(),
                'long' => $location->getLongitude(),
            ];
        }
        return new JsonResponse($json);

    }


}
