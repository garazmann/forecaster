<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
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


}
