<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CardService;
use App\Repository\CharacterRepository;
use App\Repository\FighterRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Character;
use App\Entity\Fighter;
/**
 * @Route("/game", name="game_")
 */
class GameController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function chooseCharacter(CharacterRepository $characterRepository): Response
    {
        return $this->render('game/index.html.twig',[
            'characters' => $characterRepository->findAll()
        ]);
    }

    /**
     * @Route("/select/{player1}/{player2}", name="selecting_character")
     */
    public function selectingCharacter(Character $player1, Character $player2, EntityManagerInterface $em): Response
    {
        $fighter1 = new Fighter();
        $fighter2 = new Fighter();

        $spellsP1 = $player1->getSpells();
        $colorsP1 = $player1->getColors();

        $spellsP2 = $player2->getSpells();
        $colorsP2 = $player2->getColors();

        $fighter1
            ->setPersonnage($player1)
            ->setPlayer('Player 1')
            ->setPower($player1->getPower())
            ->setToughness($player1->getToughness())
            ->setSpeed($player1->getSpeed())
            ->setHealthPoint($player1->getHealthPoint())
            ->setManaBase(0);

        foreach($spellsP1 as $spell){
            $fighter1->addSpell($spell);
        }
        foreach($colorsP1 as $color){
            $fighter1->addColor($color);
        }

        $fighter2
            ->setPersonnage($player2)
            ->setPlayer('Player 2')
            ->setPower($player2->getPower())
            ->setToughness($player2->getToughness())
            ->setSpeed($player2->getSpeed())
            ->setHealthPoint($player2->getHealthPoint())
            ->setManaBase(0);

        foreach($spellsP2 as $spell){
            $fighter2->addSpell($spell);
        }
        foreach($colorsP2 as $color){
            $fighter2->addColor($color);
        }
        dd($fighter1, $fighter2);
        $em->flush();

        return redirectToRoute('fight_index');
    }
}
