<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/fight", name="fight_")
 */
class FightController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        return $this->render('fight/index.html.twig', [
            'controller_name' => 'FightController',
        ]);
    }

    /**
     * @Route("/fight", name="fight")
     */
    public function fight(FighterRepository $fighterRepository): Response
    {
        $player1 = $fighterRepository->findByPlayer('Player 1');
        $player2 = $fighterRepository->findByPlayer('Player 2');

        return redirectToRoute('fight_index');
    }
}
