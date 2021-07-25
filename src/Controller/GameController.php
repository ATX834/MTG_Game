<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CardService;
use App\Repository\CharacterRepository;
use App\Repository\FighterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Character;
use App\Entity\Fighter;
/**
 * @Route("/game", name="game_")
 */
class GameController extends AbstractController
{
    
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
     * @Route("/", name="index")
     */
    public function chooseCharacter(CharacterRepository $characterRepository): Response
    {
        return $this->render('game/index.html.twig',[
            'characters' => $characterRepository->findByIsABonusCharacter(false),
            'bonusCharacters' => $characterRepository->findByIsABonusCharacter(true),
            'hasWinOneGame' => $this->session->get("hasWinOneGame"),
        ]);
    }

    /**
     * @Route("/select/{player1}/{player2}/{bonusStage}", name="selecting_character")
     */
    public function selectingCharacter(Character $player1, Character $player2, bool $bonusStage,EntityManagerInterface $em): Response
    {
        // dd($player1, $player2);
        if($bonusStage)
        {
            $this->session->set('bonusStage', true);
        }

        $fighter1 = new Fighter();
        $fighter2 = new Fighter();

        $spellsP1 = $player1->getSpells();
        $colorsP1 = $player1->getColors();

        $spellsP2 = $player2->getSpells();
        $colorsP2 = $player2->getColors();

        $fighter1
            ->setPersonnage($player1)
            ->setType('Player')
            ->setPlayer('Player 1')
            ->setPower($player1->getPower())
            ->setToughness($player1->getToughness())
            ->setSpeed($player1->getSpeed())
            ->setHealthPoint($player1->getHealthPoint())
            ->setMaxHealthPoint($player1->getHealthPoint())
            ->setManaBase(0)
            ->setHealthPointStartOfTurn($player1->getHealthPoint());
        
        foreach($spellsP1 as $spell){
            $fighter1->addSpell($spell);
        }
        foreach($colorsP1 as $color){
            $fighter1->addColor($color);
        }
            
        $fighter2
            ->setPersonnage($player2)
            ->setType('Player')
            ->setPlayer('Player 2')
            ->setPower($player2->getPower())
            ->setToughness($player2->getToughness())
            ->setSpeed($player2->getSpeed())
            ->setHealthPoint($player2->getHealthPoint())
            ->setMaxHealthPoint($player2->getHealthPoint())
            ->setManaBase(0)
            ->setHealthPointStartOfTurn($player2->getHealthPoint());


        foreach($spellsP2 as $spell){
            $fighter2->addSpell($spell);
        }
        foreach($colorsP2 as $color){
            $fighter2->addColor($color);
        }
        $em->persist($fighter1);
        $em->persist($fighter2);
        $em->flush();

        return $this->redirectToRoute('fight_index');
    }
}
