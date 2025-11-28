<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{
    private const WEATHER_API_URL = 'https://api.openweathermap.org/data/2.5/weather';

    private ?string $apiKey;

    public function __construct(
        private HttpClientInterface $httpClient,
        string $openweatherApiKey = null,
    ) {
        // soit tu passes la clé en param, soit via .env
        $this->apiKey = $openweatherApiKey ?? ($_ENV['WEATHER_API_KEY'] ?? null);
    }

    public function getWeatherByCity(string $city, string $lang = 'fr'): ?array
    {
<<<<<<< HEAD
        if (!$this->apiKey || !trim($city)) {
            return null;
        }

        try {
            $response = $this->httpClient->request('GET', self::WEATHER_API_URL, [
                'query' => [
                    'q'     => $city,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                    'lang'  => $lang,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            return $response->toArray(false);
        } catch (\Throwable $e) {
            return null;
        }
=======
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
>>>>>>> origin/isabelle
    }

    public function getWeatherByCoordinates(float $lat, float $lon, string $lang = 'fr'): ?array
    {
        if (!$this->apiKey) {
            return null;
        }

        try {
            $response = $this->httpClient->request('GET', self::WEATHER_API_URL, [
                'query' => [
                    'lat'   => $lat,
                    'lon'   => $lon,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                    'lang'  => $lang,
                ],
            ]);

<<<<<<< HEAD
            if ($response->getStatusCode() !== 200) {
                return null;
            }

            return $response->toArray(false);
        } catch (\Throwable $e) {
            return null;
        }
=======
            if ($response->getStatusCode() === 200) {
                return $response->toArray();
            }

            throw new \Exception('Erreur API: Code ' . $response->getStatusCode());
        } catch (\Exception $e) {
            throw new \Exception('Impossible de récupérer la météo: ' . $e->getMessage());
        }

>>>>>>> origin/isabelle
    }
}
