<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class ApiService
{

    public function getDataAsArray(string $url): array
    {
        $client = HttpClient::create();

        $response = $client->request('GET', $url);

        return $response->toArray();
    }
}