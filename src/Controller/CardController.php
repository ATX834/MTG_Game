<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ApiService;
use App\Service\CardService;

/**
* @Route("/card", name="card_")
*/
class CardController extends AbstractController
{
    /**
    * @Route("/", name="index")
    */
    public function index(ApiService $apiService, CardService $cardService): Response
    {
        $sets = $apiService->getDataAsArray("https://api.scryfall.com/sets");

        return $this->render('card/index.html.twig',[
            'sets' => $sets['data']
        ]);
    }

    /**
    * @Route("/{set}", name="show_set")
    */
    public function showSet(string $set, CardService $cardService): Response
    {
        $cards = $cardService->findCardsBySet($set);
        $images = $cardService->getCardImages($cards);

        return $this->render('card/show.html.twig', [
            'cards' => $cards,
            'images' => $images,
        ]);
    }
}
