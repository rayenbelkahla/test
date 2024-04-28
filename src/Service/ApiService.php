<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getDiseasesBySymptoms(array $symptoms): array
    {
        $response = $this->client->request(
            'POST',
            'http://localhost:5000/diagnose',
            [
                'json' => ['symptoms' => $symptoms]
            ]
        );

        if ($response->getStatusCode() === 200) {
            // Assuming the API returns a JSON response
            return $response->toArray();
        }

        return []; // Handle the case where the API does not return a 200 OK
    }
}