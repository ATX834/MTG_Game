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
        $creatures = [];

        $this->player1->setHealthPointStartOfTurn($this->player1->getHealthPoint());
        $this->player2->setHealthPointStartOfTurn($this->player2->getHealthPoint());

        if(null === $this->session->get('nbTurn'))
        {
            $this->session->set('nbTurn', 0);
        }

        if(null === $this->session->get('fightLogs'))
        {
            $this->session->set('fightLogs', [
                'Welcome to the FIGHT ARENA !'
            ]);
        }

        if($this->session->get('nbTurn') !== 0)
        {
            $this->player1->getManaBase() >= 5 ? : $this->player1->setManaBase($this->player1->getManaBase() + 1);
            $this->player2->getManaBase() >= 5 ? : $this->player2->setManaBase($this->player2->getManaBase() + 1);
        }
        
        $this->session->set('nbTurn', ($this->session->get('nbTurn') + 1));

        $this->player1->getSpeed() >= $this->player2->getSpeed() ? $this->session->set('isItYourTurn', true) :$this->session->set('isItYourTurn', false);

        $this->em->flush();

        return $this->render('fight/index.html.twig', [
            'player1' => $this->player1,
            'player2' => $this->player2,
            'nbTurn' => $this->session->get('nbTurn'),
            'fightLogs' => $this->session->get('fightLogs'),
            'winner' => null,
            'bonusStage' => $this->session->get('bonusStage')
        ]);
    }

    /**
     * @Route("/choosing/{isItSpell}/{attack}", name="choosing_attack")
     */
    public function choosingAttack(bool $isItSpell, int $attack): Response
    {
        $this->session->remove('fightLogs');
        $this->session->set('fightLogs', []);
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
        $iaChoices = ['Attack'];
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
                    'winner' => $this->player1->getId()
                ]);
            }
            
            foreach($this->player2->getSpells() as $spell)
            {
                if($as->checkIfEnoughMana($this->player2, $spell->getManaCost()))
                {
                    array_push($iaChoices, $spell);
                }
            }
            if($as->checkIfEnoughMana($this->player2, 5)){
                array_push($iaChoices, 'Ultimate');
            }
            $as->iaChoice($this->player2, $this->player1, $iaChoices);
            
            if(!$as->isAlive($this->player1))
            {
                return $this->redirectToRoute('fight_end', [
                    'winner' => $this->player2->getId()
                ]);
            }
        } else {
            
            foreach($this->player2->getSpells() as $spell)
            {
                if($as->checkIfEnoughMana($this->player2, $spell->getManaCost()))
                {
                    array_push($iaChoices, $spell);
                }
            }
            if($as->checkIfEnoughMana($this->player2, 5)){
                array_push($iaChoices, 'Ultimate');
            }
            $as->iaChoice($this->player2, $this->player1, $iaChoices);
            
            if(!$as->isAlive($this->player1))
            {
                return $this->redirectToRoute('fight_end', [
                    'winner' => $this->player2->getId()
                ]);
            }
            
            $as->attack($this->player1, $this->player2);
            
            if(!$as->isAlive($this->player2))
            {
                return $this->redirectToRoute('fight_end', [
                    'winner' => $this->player1->getId()
                ]);
            }

        }
        return $this->redirectToRoute('fight_index');
    }
    
    /**
     * @Route("/use/{spell}", name="use_spell")
     */
    public function spell(Spell $spell, ActionService $as): Response
    {
        $iaChoices = ['Attack'];

        if(!$as->checkIfEnoughMana($this->player1, $spell->getManaCost()))
        {
            $this->session->remove('fightLogs');
            $this->session->set('fightLogs', [
                "You can't use " . $spell->getName() . " with " . $this->player1->getManaBase() . " mana."
            ]);

            return $this->render('fight/index.html.twig', [
                'player1' => $this->player1,
                'player2' => $this->player2,
                'nbTurn' => $this->session->get('nbTurn'),
                'fightLogs' => $this->session->get('fightLogs'),
                'winner' => null,
                'bonusStage' => $this->session->get('bonusStage')
        ]);
        }

        if($this->session->get('isItYourTurn'))
        {
            $as->checkSpell($this->player1, $this->player2, $spell);
            
            if(!$as->isAlive($this->player2))
            {
                return $this->redirectToRoute('fight_end', [
                    'winner' => $this->player1->getId()
                ]);
            }
            
            foreach($this->player2->getSpells() as $spell)
            {
                if($as->checkIfEnoughMana($this->player2, $spell->getManaCost()))
                {
                    array_push($iaChoices, $spell);
                }
            }
            if($as->checkIfEnoughMana($this->player2, 5)){
                array_push($iaChoices, 'Ultimate');
            }
            $as->iaChoice($this->player2, $this->player1, $iaChoices);
            
            if(!$as->isAlive($this->player1))
            {
                return $this->redirectToRoute('fight_end', [
                    'winner' => $this->player2->getId()
                ]);
            }
        } else {
            
            foreach($this->player2->getSpells() as $iaSpell)
            {
                if($as->checkIfEnoughMana($this->player2, $iaSpell->getManaCost()))
                {
                    array_push($iaChoices, $iaSpell);
                }
            }
            if($as->checkIfEnoughMana($this->player2, 5)){
                array_push($iaChoices, 'Ultimate');
            }
            $as->iaChoice($this->player2, $this->player1, $iaChoices);
    
            $as->checkSpell($this->player1, $this->player2, $spell);

            if(!$as->isAlive($this->player1))
            {
                return $this->redirectToRoute('fight_end', [
                    'winner' => $this->player2->getId()
                ]);
            }
            
            $as->attack($this->player1, $this->player2);
            
            if(!$as->isAlive($this->player2))
            {
                return $this->redirectToRoute('fight_end', [
                    'winner' => $this->player1->getId()
                ]);
            }
    
        }
        return $this->redirectToRoute('fight_index');
    }

    /**
     * @Route("/ultimate", name="ultimate")
     */
    public function ultimate(ActionService $as): Response
    {
        $iaChoices = ['Attack'];

        if(!$as->checkIfEnoughMana($this->player1, 5))
        {
            $this->session->remove('fightLogs');
            $this->session->set('fightLogs', [
                "You can't cast your ultimate with " . $this->player1->getManaBase() . " mana."
            ]);

            return $this->render('fight/index.html.twig', [
                'player1' => $this->player1,
                'player2' => $this->player2,
                'nbTurn' => $this->session->get('nbTurn'),
                'fightLogs' => $this->session->get('fightLogs'),
                'winner' => null,
                'bonusStage' => $this->session->get('bonusStage')
            ]);
        }

        if($this->session->get('isItYourTurn'))
        {
            $as->checkUltimate($this->player1, $this->player2);
            
            if(!$as->isAlive($this->player2))
            {
                return $this->redirectToRoute('fight_end', [
                    'winner' => $this->player1->getId()
                ]);
            }
            
            foreach($this->player2->getSpells() as $spell)
            {
                if($as->checkIfEnoughMana($this->player2, $spell->getManaCost()))
                {
                    array_push($iaChoices, $spell);
                }
            }
            if($as->checkIfEnoughMana($this->player2, 5)){
                array_push($iaChoices, 'Ultimate');
            }
            $as->iaChoice($this->player2, $this->player1, $iaChoices);
            
            if(!$as->isAlive($this->player1))
            {
                return $this->redirectToRoute('fight_end', [
                    'winner' => $this->player2->getId()
                ]);
            }
        } else {
            
            foreach($this->player2->getSpells() as $iaSpell)
            {
                if($as->checkIfEnoughMana($this->player2, $iaSpell->getManaCost()))
                {
                    array_push($iaChoices, $iaSpell);
                }
            }
            if($as->checkIfEnoughMana($this->player2, 5)){
                array_push($iaChoices, 'Ultimate');
            }

            $as->iaChoice($this->player2, $this->player1, $iaChoices);
    
            
            if(!$as->isAlive($this->player1))
            {
                return $this->redirectToRoute('fight_end', [
                    'winner' => $this->player2->getId()
                ]);
            }
            $as->checkUltimate($this->player1, $this->player2);
                        
            if(!$as->isAlive($this->player2))
            {
                return $this->redirectToRoute('fight_end', [
                    'winner' => $this->player1->getId()
                ]);
            }
    
        }
        return $this->redirectToRoute('fight_index');
    }

    /**
     * @Route("/end/{winner}", name="end")
     */
    public function fightEnd(Fighter $winner)
    {
        if(null === $this->session->get('hasWinOneGame') && $winner->getPlayer() === 'Player 1')
        {
            $this->session->set('hasWinOneGame', true);
        }

        return $this->render('fight/index.html.twig', [
            'player1' => $this->player1,
            'player2' => $this->player2,
            'nbTurn' => $this->session->get('nbTurn'),
            'fightLogs' => $this->session->get('fightLogs'),
            'winner' => ($winner->getPlayer() === 'Player 1' ? $this->player1 :  $this->player2),
            'bonusStage' => $this->session->get('bonusStage')
        ]);
    }

    /**
     * @Route("/destroy", name="destroy_game_data")
     */
    public function destroyGameData(FighterRepository $fighterRepository): Response
    {
        $this->session->remove('nbTurn');
        $this->session->remove('isItYourTurn');
        $this->session->remove('fightLogs');
        $this->session->remove('bonusStage');
        
        foreach($fighterRepository->findAll() as $fighter)
        {
            $fighterRepository->destroy($fighter);
        }

        return $this->redirectToRoute('game_index');
    }

    
}
