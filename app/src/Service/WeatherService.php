
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
                    'lang'=> $lang,
                ],
            ]);

            if ($response->getStatusCode() === 200) {
               $response->toArray();
            }
        } catch (\Exception $e) {
            $reponse="Météo indisponible pour le moment";
        }
        return $reponse;
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
                $response->toArray();
            }
        } catch (\Exception $e) {
            $reponse="Météo indisponible pour le moment";
        }
        return $reponse;
    }
}