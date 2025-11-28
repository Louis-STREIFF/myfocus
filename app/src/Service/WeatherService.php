<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{
    private const WEATHER_API_URL = 'https://api.openweathermap.org/data/2.5/weather';

    public function __construct(
        private HttpClientInterface $httpClient,
        private string $openweatherApiKey,
    ) {}

    public function getWeatherByCity(string $city, string $lang = 'fr'): array
    {
            try {
                    $response = $this->httpClient->request('GET', self::WEATHER_API_URL, [
                        'query' => [
                            'q' => $city,
                            'appid' => $this->openweatherApiKey,
                            'units' => 'metric',
                            'lang' => $lang,
                        ],
                    ]);

                    if ($response->getStatusCode() === 200) {
                        return $response->toArray();
                    }

                    throw new \Exception('Erreur API: Code ' . $response->getStatusCode());
                } catch (\Exception $e) {
                    throw new \Exception('Impossible de récupérer la météo: ' . $e->getMessage());
                }
    }

    public function getWeatherByCoordinates(float $lat, float $lon, string $lang = 'fr'): array
    {
        try {
            $response = $this->httpClient->request('GET', self::WEATHER_API_URL, [
                'query' => [
                    'lat' => $lat,
                    'lon' => $lon,
                    'appid' => $this->openweatherApiKey,
                    'units' => 'metric',
                    'lang' => $lang,
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                return $response->toArray();
            }

            throw new \Exception('Erreur API: Code ' . $response->getStatusCode());
        } catch (\Exception $e) {
            throw new \Exception('Impossible de récupérer la météo: ' . $e->getMessage());
        }

    }
}