<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FighterRepository;
use App\Entity\Fighter;
use App\Entity\Spell;
use App\Service\ActionService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/fight", name="fight_")
 */
class FightController extends AbstractController
{    
    private Fighter $player1;

    private Fighter $player2;

    private $session;

    private $em;
    
    public function __construct(FighterRepository $fighterRepository, SessionInterface $session, EntityManagerInterface $em)
    {
        $this->player1 = $fighterRepository->findOneByPlayer('Player 1');
        $this->player2 = $fighterRepository->findOneByPlayer('Player 2');
        $this->session = $session;
        $this->em = $em;
    }
    /**
     * @Route("", name="index")
     */
    public function index(FighterRepository $fighterRepository): Response
    {       
        if(null === $this->session->get('nbTurn'))
        {
            $this->session->set('nbTurn', 0);
        }

        if($this->session->get('nbTurn') !== 0)
        {
            $this->player1->getManaBase() >= 5 ? : $this->player1->setManaBase($this->player1->getManaBase() + 1);
            $this->player2->getManaBase() >= 5 ? : $this->player2->setManaBase($this->player2->getManaBase() + 1);
        }
        
        $this->session->set('nbTurn', ($this->session->get('nbTurn') + 1));

        $this->player1->getSpeed() >= $this->player2->getSpeed() ? $this->session->set('isItYourTurn', true) :$this->session->set('isItYourTurn', false);

        // $this->session->remove('nbTurn');
        $this->em->flush();

        return $this->render('fight/index.html.twig', [
            'player1' => $this->player1,
            'player2' => $this->player2,
            'nbTurn' => $this->session->get('nbTurn')
        ]);
    }

    /**
     * @Route("/choosing/{isItSpell}/{attack}", name="choosing_attack")
     */
    public function choosingAttack(bool $isItSpell, int $attack): Response
    {
        return $isItSpell === true ? $this->redirectToRoute('fight_use_spell', [
            'spell' => $attack
        ])
         : $this->redirectToRoute('fight_attack', [
             'attack' => $attack
         ]);
    }

    /**
     * @Route("/attack/{attack}", name="attack")
     */
    public function attack(int $attack, ActionService $as): Response
    {
        if($attack === 0)
        {
            return $this->redirectToRoute('fight_ultimate');
        }
        
        if($this->session->get('isItYourTurn'))
        {
            $as->attack($this->player1, $this->player2);
            
            if(!$as->isAlive($this->player2))
            {
                return $this->redirectToRoute('fight_end', [
                    'loser' => $this->player2->getId()
                ]);
            }
            
            // $as->attack($this->player2, $this->player1);
            $as->iaChoice($this->player2, $this->player1);

            if(!$as->isAlive($this->player1))
            {
                return $this->redirectToRoute('fight_end', [
                    'loser' => $this->player1->getId()
                ]);
            }
        } else {
            
            // $as->attack($this->player2, $this->player1);
            $as->iaChoice($this->player2, $this->player1);
            
            if(!$as->isAlive($this->player1))
            {
                return $this->redirectToRoute('fight_end', [
                    'loser' => $this->player1->getId()
                ]);
            }
            
            $as->attack($this->player1, $this->player2);
            if(!$as->isAlive($this->player2))
            {
                return $this->redirectToRoute('fight_end', [
                    'loser' => $this->player2->getId()
                ]);
            }

        }
        return $this->redirectToRoute('fight_index');
    }

    /**
     * @Route("/use/{spell}", name="use_spell")
     */
    public function spell(Spell $spell): Response
    {
        // implémenter l'utilisation des spells
        return $this->redirectToRoute('fight_index');
    }

    /**
     * @Route("/ultimate", name="ultimate")
     */
    public function ultimate(): Response
    {
        // implémenter l'utilisation des ulti
        return $this->redirectToRoute('fight_index');
    }

    /**
     * @Route("/end/{loser}", name="end")
     */
    public function fightEnd(Fighter $loser)
    {
        $loser->getPlayer() === 'Player 1' ? dd('you lose') : dd('you win');
    }

    /**
     * @Route("/destroy", name="destroy_game_data")
     */
    public function destroyGameData(FighterRepository $fighterRepository): Response
    {
        $this->session->remove('nbTurn');
        $this->session->remove('isItYourTurn');
        foreach($fighterRepository->findAll() as $fighter)
        {
            $fighterRepository->destroy($fighter);
        }

        return $this->redirectToRoute('game_index');
    }

    
}
