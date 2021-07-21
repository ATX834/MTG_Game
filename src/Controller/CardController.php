<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ApiService;

class CardController extends AbstractController
{
    /**
    * @Route("/", name="card_index")
    */
    public function index(ApiService $apiService): Response
    {
        $url = 'https://api.scryfall.com/cards/search?order=cmc&q=c%3Ared+pow%3D3';

        $array = $apiService->getDataAsArray($url);
        $array = $array['data'];

        return $this->render('card/index.html.twig', [
            'cards' => $array,
        ]);
    }
}
