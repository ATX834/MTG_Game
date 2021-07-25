<?php

namespace App\Service;

use App\Entity\Fighter;
use App\Entity\Spell;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FightLogService 
{
 
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function logAttack(Fighter $attacker,Fighter $target, int $damage): void
    {
        $logs = $this->session->get('fightLogs');

        if($attacker->getPersonnage()->getIsABonusCharacter() && $target->getPersonnage()->getIsABonusCharacter())
        {
            array_push($logs, 'Un tir de NERF dans la gueule !');
            array_push($logs, $target->getPersonnage()->getName() . " prends " . $damage . " dégât(s) à cause de " . $attacker->getPersonnage()->getName() . "." );
        } else {
            $attacker->getPlayer() === 'Player 1' 
            ? array_push($logs, "You deal " . $damage . " damage to " . $target->getPersonnage()->getName() . "." )
            : array_push($logs, $attacker->getPersonnage()->getName() . " deals " . $damage . " damage to you.");
        }

        $this->session->set('fightLogs', $logs);
    }

    public function logUltimate(Fighter $attacker,Fighter $target): void
    {
        $logs = $this->session->get('fightLogs');

        if($attacker->getPersonnage()->getName() === 'Gideon')
        {
            if($attacker->getPlayer() === 'Player 1' )
            {
                array_push($logs, "You use your Ultimate for 5 mana." );
                array_push($logs, "It deals 6 damages to " . $target->getPersonnage()->getName() . ".");
                array_push($logs, "Your max HP is up to " . $attacker->getMaxHealthPoint() . ".");
                array_push($logs, "You gain 5 Health points.");
                
            } else {
                array_push($logs, $attacker->getPersonnage()->getName() ." uses his/her Ultimate for 5 mana." );
                array_push($logs, "It deals 6 damages to you.");
                array_push($logs, $attacker->getPersonnage()->getName() . "'s max HP is up to " . $attacker->getMaxHealthPoint() . ".");
                array_push($logs, $attacker->getPersonnage()->getName() . " gains 5 Health points.");
            }
        }
        if($attacker->getPersonnage()->getName() === 'Chandra')
        {
            if($attacker->getPlayer() === 'Player 1' )
            {
                array_push($logs, "You use your Ultimate for 5 mana." );
                array_push($logs, "It deals 8 damages to " . $target->getPersonnage()->getName() . ".");
                array_push($logs, $target->getPersonnage()->getName() . "'s max HP is down to " . $attacker->getMaxHealthPoint() . ".");
                array_push($logs, $target->getPersonnage()->getName() . " loses 2 power points.");
                
            } else {
                array_push($logs, $attacker->getPersonnage()->getName() ." uses his/her Ultimate for 5 mana." );
                array_push($logs, "It deals 8 damages to you.");
                array_push($logs, "Your max HP is up to " . $target->getMaxHealthPoint() . ".");
                array_push($logs, "You lose 2 power points.");
            }
        }
        if($attacker->getPersonnage()->getName() === 'Audrey')
        {
            array_push($logs, "Tu pars avant 17h ?" );
            array_push($logs, "T'as pas signé la feuille ?" );
            array_push($logs, "T'AS DIT COMBIEN DE DU COUP EN PREZ ?" );
            array_push($logs, "Audrey recharge le snipe et flingue " . $target->getPersonnage()->getName() . " !");
        }
        if($attacker->getPersonnage()->getName() === 'Vincent')
        {
            array_push($logs, "Vincent alpague " . $target->getPersonnage()->getName() . "." );
            array_push($logs, "Ils vont aux Chimères ensemble." );
            array_push($logs, "Puis au Carrouf pour la petite bière de route." );
            array_push($logs, "Bizarrement, Vince reste dormir chez " . $target->getPersonnage()->getName() . ".");
        }
        if($attacker->getPersonnage()->getName() === 'Geoffroy')
        {
            array_push($logs, "Geoffroy est pas content des prez des JS aujourd'hui." );
            array_push($logs, "Il force "  . $target->getPersonnage()->getName() . " à faire un Clash Of Codes" );
            array_push($logs, "La torture durera quelques heures. 😢" );
        }

        $this->session->set('fightLogs', $logs);
    }

    public function logSpell(Fighter $attacker,Fighter $target, Spell $spell): void
    {
        $logs = $this->session->get('fightLogs');

        if($spell->getName() === 'Lightning Bolt')
        {
            if($attacker->getPlayer() === 'Player 1' )
            {
                array_push($logs, "You cast Lightning Bolt with " . $spell->getManaCost() ." mana." );
                array_push($logs, "It deals 5 damage to " . $target->getPersonnage()->getName() . ".");
            } else {
                array_push($logs, $attacker->getPersonnage()->getName() ." cast Lightning Bolt with " . $spell->getManaCost() ." mana." );
                array_push($logs, "It deals 5 damage to you.");
            }
        }
        if($spell->getName() === 'Chandra Embercat')
        {

        }
        if($spell->getName() === 'Path To Exile')
        {

        }
        if($spell->getName() === 'Revitalize')
        {
            if($attacker->getPlayer() === 'Player 1' )
            {
                array_push($logs, "You cast Revitalize with " . $spell->getManaCost() ." mana." );
                array_push($logs, "You gain 3 health points.");
                array_push($logs, "You gain 1 " . $this->session->get('buff') . " point.");
            } else {
                array_push($logs, $attacker->getPersonnage()->getName() . " cast Revitalize with " . $spell->getManaCost() ." mana." );
                array_push($logs, $attacker->getPersonnage()->getName() . " gains 3 health points.");
                array_push($logs, $attacker->getPersonnage()->getName() . " gains 1 " . $this->session->get('buff') . " point.");
            }
            $this->session->remove('buff');
        }
        if($spell->getName() === 'Adamant Will')
        { 
            if($attacker->getPlayer() === 'Player 1' )
            {
                array_push($logs, "You cast Adamant Will with " . $spell->getManaCost() ." mana." );
                array_push($logs, "You've become INDESTRUCTIBLE.");
            } else {
                array_push($logs, $attacker->getPersonnage()->getName() . " cast Adamant Will with " . $spell->getManaCost() ." mana." );
                array_push($logs, $attacker->getPersonnage()->getName() . " has become INDESTRUCTIBLE.");
            }
        }
        if($spell->getName() === 'Fiery Confluence')
        {
            if($attacker->getPlayer() === 'Player 1' )
            {
                array_push($logs, "You cast Fiery Confluence with " . $spell->getManaCost() ." mana." );
                array_push($logs, "It deals 2 damage to " . $target->getPersonnage()->getName() . ".");
                array_push($logs, $target->getPersonnage()->getName() ." loses 1 toughness point.");
                array_push($logs, "You gain 1 power point.");
            } else {
                array_push($logs, $attacker->getPersonnage()->getName() ." cast Fiery Confluence with " . $spell->getManaCost() ." mana." );
                array_push($logs, "It deals 2 damage to you.");
                array_push($logs, "You lose 1 toughness point.");
                array_push($logs, $attacker->getPersonnage()->getName() ." gains 1 power point.");
            }
        }
        if($spell->getName() === 'Sniper')
        {
            array_push($logs, $attacker->getPersonnage()->getName() . " snipe " . $target->getPersonnage()->getName() . " et lui inflige 5 dégâts.");
        }
        if($spell->getName() === 'Remote')
        {
            array_push($logs, $attacker->getPersonnage()->getName() . " est en remote.");
            array_push($logs, "Il est INTOUCHABLE.");

        }
        if($spell->getName() === 'Grimm ambrée')
        {
            array_push($logs, "Longue journée ...");
            array_push($logs, $attacker->getPersonnage()->getName() . " se met une pinte de Grim Ambrée dans le nez.");
            array_push($logs, $attacker->getPersonnage()->getName() . " regagne 3 points de vie.");
            array_push($logs, $attacker->getPersonnage()->getName() . " se sent déjà mieux.");
        }
        if($spell->getName() === 'Pas partir avant 17h')
        {
            array_push($logs, $target->getPersonnage()->getName() . " part avant 17h ...");
            array_push($logs, $attacker->getPersonnage()->getName() . " l'a vue.");
            array_push($logs, $target->getPersonnage()->getName() . " va prendre misère.");
            array_push($logs, $target->getPersonnage()->getName() . " perd deux points de vie et un point d'endurance.");
            array_push($logs, $attacker->getPersonnage()->getName() . " gagne un point de force.");
        }

        $this->session->set('fightLogs', $logs);
    }
}