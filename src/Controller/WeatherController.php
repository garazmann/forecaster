<?php

namespace App\Controller;

use App\Model\HoralApiDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class WeatherController extends AbstractController
{
    #[Route('/weather/api', name: 'app_apiweather_horalrika')]
    public function indexApi(
        #[MapQueryString] ?HoralApiDTO $dto = null 
    ): Response
    {
        if (!$dto){
            $dto = new HoralApiDTO();
            $dto->treshold = 50;
            $dto->trials = 1;
        }

        for ($i=0; $i < $dto->trials; $i++) { 
            $draw = random_int(0, 100);
            $forecast = $draw < $dto->treshold ? 'Bude pršet' : 'Bude svítit sluníčko';
            $forecasts[] = $forecast;
        }

        $json = [
            'forecasts' => $forecasts,
            'treshold' => $dto->treshold
        ];

        //return new JsonResponse($json);
        return $this->json($json);

    }



    #[Route('/weather/{treshold<\d+>}', name: 'app_weather_horalrika')]
    public function index(
        Request $request,
        RequestStack $requestStack,
        ?int $treshold = null
    ): Response
    {
        $session = $requestStack->getSession();
        if ($treshold) {
            $session->set('treshold', $treshold);
        } else {
            $treshold = $session->get('treshold', 50);
            $this->addFlash(
                'info',
                "Treshold nastaven na $treshold"
            );
        }

        $trials = $request->get('trials', 1); 
        $forecasts = [];

        for ($i=0; $i < $trials; $i++) { 
            $draw = random_int(0, 100);
            $forecast = $draw < $treshold ? 'Bude pršet' : 'Bude svítit sluníčko';
            $forecasts[] = $forecast;
        }

        return $this->render('weather/index.html.twig', [
            'forecasts' => $forecasts,
            'treshold' => $treshold
        ]);

    }





    #[Route('/weather/{guess}')]
    public function indexGuess(string $guess): Response
    {

        $avaiableGuesses = ['snezi', 'prsi', 'mrholi'];

        if(!in_array($guess, $avaiableGuesses)){
            throw $this->createNotFoundException('Tato možnost nenalezena.');
        }

        $forecast = "Dnes bude nejspíš počasí: $guess";

        return $this->render('weather/index.html.twig', [
            'forecasts' => [$forecast]
        ]);
    }

    // #[Route('/weather/{countryCode}/{city}')]
    // public function forecast(string $countryCode, string $city): Response
    // {
        

    //     return $this->render('weather/index.html.twig', [
    //         'countryCode' => $countryCode,
    //         'city' => $city
    //     ]);
    // }




}
