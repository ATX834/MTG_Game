<?php

namespace App\Service;

use App\Entity\Fighter;
use App\Entity\Spell;
use App\Ultimate\ChandraUltimate;
use App\Ultimate\GideonUltimate;
use App\Service\FightLogService;
use App\Repository\SpellRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ActionService
{
    private $em;

    private $spellRepository;

    private $fightLog;

    private $session;

    public const MANA_COST = 5;

    public function __construct(EntityManagerInterface $em, SpellRepository $spellRepository, FightLogService $fightLog, SessionInterface $session)
    {
        $this->em = $em;
        $this->spellRepository = $spellRepository;
        $this->fightLog = $fightLog;
        $this->session = $session;
    }    

    public function checkSpell(Fighter $attacker, Fighter $defender, Spell $spell)
    {
        //créer phrase de retour pour savoir ce qui se passe en affichage
        if($spell->getName() === 'Lightning Bolt' || $spell->getName() === 'Sniper')
        {
            $this->lightingBolt($attacker, $defender);
        }
        if($spell->getName() === 'Chandra Embercat')
        {
            $this->chandraEmbercat($attacker, $spell);
        }
        if($spell->getName() === 'Path To Exile')
        {
            $this->pathToExile($attacker, $defender);
        }
        if($spell->getName() === 'Revitalize' || $spell->getName() === 'Grimm ambrée')
        {
            $this->revitalize($attacker);
        }
        if($spell->getName() === 'Adamant Will' || $spell->getName() === 'Remote')
        {
            $this->adamantWill($attacker);
        }
        if($spell->getName() === 'Fiery Confluence' || $spell->getName() === 'Pas partir avant 17h')
        {
            $this->fieryConfluence($attacker, $defender);
        }
        $this->fightLog->logSpell($attacker, $defender, $spell);
    }

    public function checkUltimate(Fighter $attacker, Fighter $defender)
    {
        if($attacker->getPersonnage()->getName() === 'Chandra' || $attacker->getPersonnage()->getName() === 'Audrey')
        {
            $this->chandraUlti($attacker, $defender);
        }    
        if($attacker->getPersonnage()->getName() === 'Gideon' || $attacker->getPersonnage()->getName() === 'Vincent' || $attacker->getPersonnage()->getName() === 'Geoffroy' )
        {
            $this->gideonUlti($attacker, $defender);
        }
        $this->fightLog->logUltimate($attacker, $defender);    
    }

    public function checkIfEnoughMana(Fighter $caster,int $manaCost)
    {
        return $caster->getManaBase() >= $manaCost ? true : false; 
    }

    private function lightingBolt(Fighter $attacker, Fighter $defender): void
    {
        $defender->setHealthPoint($defender->getHealthPoint() - 5);
        $attacker->setManaBase($attacker->getManabase() - 2);
        
        $this->em->flush();
    }

    private function chandraEmbercat(Fighter $caster, Spell $spell): void
    {
        $creature = new Fighter();
        // voir comment créer une invocation 
        $creature
            ->setPersonnage($caster)
            ->setType('Spell')
            ->setPlayer($caster->getPlayer())
            ->setPower($spell->getPower())
            ->setToughness($spell->getToughness())
            ->setSpeed($spell->getSpeed())
            ->setHealthPoint($spell->getToughness())
            ->setMaxHealthPoint($spell->getToughness())
            ->setManaBase(0);

        $caster->setManaBase($caster->getManabase() - 2);
        
        $this->em->flush();
    }

    private function pathToExile(Fighter $attacker, Fighter $defender): void
    {
        $defender->setHealthPoint(0);

        $attacker->setManaBase($attacker->getManabase() - 2);

        $this->em->flush();
    }

    private function revitalize(Fighter $attacker): void
    {
        $attacker->setHealthPoint($attacker->getHealthPoint() + 3);

        $buff = rand(1,3);

        if($buff === 1)
        {
            $attacker->setPower($attacker->getPower() + 1);
            $this->session->set('buff', 'power');
        }
        if($buff === 2)
        {
            $attacker->setToughness($attacker->getToughness() + 1);
            $this->session->set('buff', 'toughness');            
        }
        if($buff === 3)
        {
            $attacker->setSpeed($attacker->getSpeed() + 1);            
            $this->session->set('buff', 'speed');
        }

        $attacker->setManaBase($attacker->getManabase() - 3);
     
        $this->em->flush();
    }

    private function adamantWill(Fighter $attacker): void
    {
        $attacker->setHealthPoint($attacker->getHealthPointStartOfTurn());

        $attacker->setManaBase($attacker->getManabase() - 3);
     
        $this->em->flush();
    }

    private function fieryConfluence(Fighter $attacker, Fighter $defender): void
    {
        $defender->setHealthPoint($defender->getHealthPoint() - 2);
        
        $defender->setToughness($defender->getToughness() - 1);

        $attacker->setPower($attacker->getPower() + 1);

        $attacker->setManaBase($attacker->getManabase() - 4);
     
        $this->em->flush();
    }

    public function attack(Fighter $attacker, Fighter $defender): void
    {
        $attackCalc = (($attacker->getPower() * (rand(9, 18) / 10)) + $attacker->getSpeed() / 2);
        $defenseCalc = ($defender->getToughness() + ($defender->getSpeed((rand(8, 13) / 10)) / 2));
                
        $damage = floor($attackCalc - $defenseCalc);
        
        $damage <= 0.0 ? $damage = 1 : $damage = $damage;

        $defender->setHealthPoint($defender->getHealthPoint() - $damage);

        $this->em->flush();

        $this->fightLog->logAttack($attacker, $defender, $damage);
    }

    public function chandraUlti(Fighter $chandra, Fighter $target)
    {
        $target->setHealthPoint($target->getHealthPoint() - 8);
        $target->setMaxHealthPoint($target->getMaxHealthPoint() - 2);
        $target->setPower($target->getPower() - 2);
        
        $chandra->setManaBase($chandra->getManaBase() - self::MANA_COST);
        
        $this->em->flush();
    }

    public function gideonUlti(Fighter $gideon, Fighter $target)
    {
        $target->setHealthPoint($target->getHealthPoint() - 6);
        
        $gideon->setMaxHealthPoint($target->getMaxHealthPoint() + 2);
        $gideon->setHealthPoint($target->getHealthPoint() + 5);

        $gideon->setManaBase($gideon->getManaBase() - self::MANA_COST);

        $this->em->flush();
    }

    public function iaChoice(Fighter $ia, Fighter $human, array $choices): void
    {
        $randIndex = array_rand($choices);

        $choice = $choices[$randIndex];

        if($choice === 'Attack')
        {
            $this->attack($ia, $human);
        } elseif($choice === 'Ultimate')
        {
            $this->checkUltimate($ia, $human);            
        } else {
            $this->checkSpell($ia, $human, $choice);
        }
    }

    public function isAlive(Fighter $fighter): bool
    {
        return $fighter->getHealthPoint() <= 0 ? false : true;
    }

}

?>