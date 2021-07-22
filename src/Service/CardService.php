<?php

namespace App\Service;

use App\Service\ApiService;

class CardService
{
    private $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function findCardsBySet(string $set): array
    {
        $url = 'https://api.scryfall.com/cards/search?q=set%3D' . $set;

        $array = $this->apiService->getDataAsArray($url);
        $array = $array['data'];

        return $array;
    }

    public function getCardImages(array $array): array
    {
        $images = [];

        foreach($array as $item)
        {
            foreach($item as $key => $element)
            {
                if($key === 'image_uris')
                {
                    array_push($images, $element);
                }                
            }
        }
        
        return $images;
    }
}