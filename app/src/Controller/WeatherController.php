<?php
namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}', name: 'weather_city')]
    public function weatherByCity(string $city, WeatherService $weatherService): Response
    {
        try {
            $weather = $weatherService->getWeatherByCity($city);
            
            return $this->render('weather/index.html.twig', [
                'weather' => $weather,
                'city' => $city,
            ]);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('home');
        }
    }

    #[Route('/weather/coordinates/{lat}/{lon}', name: 'weather_coordinates')]
    public function weatherByCoordinates(float $lat, float $lon, WeatherService $weatherService): Response
    {
        try {
            $weather = $weatherService->getWeatherByCoordinates($lat, $lon);
            
            return $this->render('weather/index.html.twig', [
                'weather' => $weather,
            ]);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('home');
        }
    }
}