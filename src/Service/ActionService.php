<?php

namespace App\Service;

use App\Entity\Fighter;
use App\Entity\Spell;
use App\Repository\SpellRepository;
use Doctrine\ORM\EntityManagerInterface;

class ActionService
{
    private $em;

    private $spellRepository;

    public const IA_ACTIONS = [
        'attack',
        'spell 1',
        'spell 2',
        'ultimate'
    ];

    public function __construct(EntityManagerInterface $em, SpellRepository $spellRepository)
    {
        $this->em = $em;
        $this->spellRepository = $spellRepository;
    }    

    public function checkSpell(Fighter $attacker, Fighter $defender, Spell $spell)
    {
        //créer phrase de retour pour savoir ce qui se passe en affichage
        if($spell->getName() === 'Lightning Bolt')
        {
            self::lightingBolt($attacker, $defender);
        }
        if($spell->getName() === 'Chandra Embercat')
        {
            self::chandraEmbercat($attacker, $spell);
        }
        if($spell->getName() === 'Path To Exile')
        {
            self::pathToExile($attacker, $defender);
        }
        if($spell->getName() === 'Revitalize')
        {
            self::revitalize($attacker);
        }
    }

    public function checkUltimate(Fighter $attacker, Fighter $defender)
    {
        // écrire code des ultis
    }

    public function checkIfEnoughMana(Fighter $caster,int $manaCost)
    {
        return $caster->getManaBase() >= $manaCost ? true : false; 
    }

    private function lightingBolt(Fighter $attacker, Fighter $defender): void
    {
        $defender->setHealthPoint($defender->getHealthPoint() - 3);
        $attacker->setManaBase($attacker->getManabase() - 2);
        
        $this->em->flush();
    }

    private function chandraEmbercat(Fighter $attacker, Spell $spell): void
    {
        // voir comment créer une invocation 
        $creature
            ->setPersonnage($attacker)
            ->setType('Spell')
            ->setPlayer($attacker->getPlayer())
            ->setPower($spell->getPower())
            ->setToughness($spell->getToughness())
            ->setSpeed($spell->getSpeed())
            ->setHealthPoint($spell->getToughness())
            ->setMaxHealthPoint($spell->getToughness())
            ->setManaBase(0);

        $attacker->setManaBase($attacker->getManabase() - 2);

        // $this->em->persist($creature);
        
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
        }
        if($buff === 2)
        {
            $attacker->setToughness($attacker->getToughness() + 1);
            
        }
        if($buff === 3)
        {
            $attacker->setSpeed($attacker->getSpeed() + 1);            
        }

        $attacker->setManaBase($attacker->getManabase() - 3);
     
        $this->em->flush();
    }

    public function attack(Fighter $attacker, Fighter $defender): void
    {
        // retravailler le calcul de dégât
        $damage = floor($attacker->getPower() - ($defender->getToughness()) * rand(1, 1.5));

        $damage === 0 ? $damage += 1 : $damage = $damage;

        dd($damage, $attacker);

        $defender->setHealthPoint($defender->getHealthPoint() - $damage);

        $this->em->flush();
    }

    public function iaChoice(Fighter $ia, Fighter $human): void
    {
        $randIndex = array_rand(self::IA_ACTIONS);

        $iaChoice = self::IA_ACTIONS[$randIndex];

        if($iaChoice === 'attack')
        {
            $this->attack($ia, $human);
        }
        if($iaChoice === 'spell 1' || $iaChoice === 'spell 2')
        {
            foreach($ia->getSpells() as $key => $spell)
            {
                if($iaChoice === 'spell 1')
                {
                    if($this->checkIfEnoughMana($ia, ($ia->getSpells()[0])->getManaCost()))
                    {
                        $this->checkSpell($ia, $human, $ia->getSpells()[0]);
                    }
                    // comprendre d'où vient le problème de leak de mémoire en récursivité
                    //  else 
                    // {
                    //     $this->iaChoice($ia, $human);
                    // }
                }
                if($iaChoice === 'spell 2')
                {
                    if($this->checkIfEnoughMana($ia, ($ia->getSpells()[1])->getManaCost()))
                    {
                        $this->checkSpell($ia, $human, $ia->getSpells()[1]);
                    }
                    //  else 
                    // {
                    //     $this->iaChoice($ia, $human);
                    // }
                }
            }
        }
        // implémenter ulti pour les deux personnages
        // if($iaChoice === 'ultimate')
        // {
        //     if($this->checkIfEnoughMana($ia, 5))
        //     {
        //         $this->checkUltimate($ia, $defender);
        //     } else 
        //     {
        //         $this->iaChoice($ia, $human);
        //     } 
        // }
    }

    public function isAlive(Fighter $fighter): bool
    {
        return $fighter->getHealthPoint() <= 0 ? false : true;
    }
}

?>