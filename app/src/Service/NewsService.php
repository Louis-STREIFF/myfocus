<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewsService
{
    private ?string $apiKey;

    public function __construct(
        private HttpClientInterface $client,
        string $newsApiKey = null,
    ) {
        $this->apiKey = $newsApiKey ?? ($_ENV['NEWS_API_KEY'] ?? null);
    }

    public function getNewsForKeywords(?string $keywords): array
    {
        if (!$this->apiKey || !$keywords || !trim($keywords)) {
            return [];
        }

        // "symfony, docker, php" -> ["symfony", "docker", "php"]
        $parts = array_filter(array_map('trim', explode(',', $keywords)));
        if (empty($parts)) {
            return [];
        }

        // "symfony OR docker OR php"
        $queryString = implode(' OR ', $parts);

        $response = $this->client->request('GET', 'https://newsapi.org/v2/everything', [
            'query' => [
                'q'        => $queryString,
                'language' => 'fr',
                'sortBy'   => 'publishedAt',
                'pageSize' => 5,
                'apiKey'   => $this->apiKey,
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            return [];
        }

        $data = $response->toArray(false);

        if (($data['status'] ?? null) !== 'ok') {
            return [];
        }

        return $data['articles'] ?? [];
    }
}
