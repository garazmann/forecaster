<?php

namespace App\Controller;

use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/location-dummy')]
class LocationController extends AbstractController
{
    #[Route('/create', name: 'app_location_create')]
    public function create(EntityManagerInterface $entityManager): JsonResponse
    {
        $location = new Location();
        $location
            ->setName('Budějovice')
            ->setCountryCode('CZ')
            ->setLatitude(48.975658)
            ->setLongitude(14.480255)
            ;
            $entityManager->persist($location);

            $entityManager->flush();

            return new JsonResponse([
                'id' => $location->getId()
            ]);

    }
}
