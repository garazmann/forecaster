<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class WeatherController extends AbstractController
{
    #[Route('/weather/{treshold<\d+>?50}', host: 'api.localhost', name: 'app_apiweather_horalrika')]
    public function indexApi(int $treshold): Response
    {
        $draw = random_int(0, 100);

        $forecast = $draw < $treshold ? 'Bude pršet' : 'Bude svítit sluníčko';

        //return $this->render('weather/index.html.twig', [
        //    'forecast' => $forecast]);

        $json = [
            'forecast' => $forecast,
            'self' => $this->generateUrl(
                'app_apiweather_horalrika',
                 ['treshold' => $treshold],
                 UrlGeneratorInterface::ABSOLUTE_URL
                 )
        ];

        return new JsonResponse($json);

    }

    #[Route('/weather/{treshold<\d+>?50}', name: 'app_weather_horalrika')]
    public function index(int $treshold): Response
    {
        $draw = random_int(0, 100);

        $forecast = $draw < $treshold ? 'Bude pršet' : 'Bude svítit sluníčko';

        return $this->render('weather/index.html.twig', [
            'forecast' => $forecast]);

    }



    // #[Route('/weather/{guess}')]
    // public function indexGuess(string $guess): Response
    // {
    //     $draw = random_int(0, 100);

    //     $forecast = "Dnes bude nejspíš počasí: $guess";

    //     return $this->render('weather/index.html.twig', [
    //         'forecast' => $forecast
    //     ]);
    // }

    #[Route('/weather/{countryCode}/{city}')]
    public function forecast(string $countryCode, string $city): Response
    {
        

        return $this->render('weather/index.html.twig', [
            'countryCode' => $countryCode,
            'city' => $city
        ]);
    }




}
