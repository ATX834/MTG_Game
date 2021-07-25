<?php

namespace App\Ultimate;

use App\Entity\Fighter;
use Doctrine\ORM\EntityManagerInterface;

class GideonUltimate
{    
    public const MANA_COST = 5;

    public function execute(Fighter $gideon, Fighter $target)
    {
        $target->setHealthPoint($target->getHealthPoint() - 6);
        
        $target->setMaxHealthPoint($target->getMaxHealthPoint() + 2);
        $target->setHealthPoint($target->getHealthPoint() + 5);

        $gideon->setManaBase($gideon->getManaBase() - self::MANA_COST);

        $this->em->flush();
    }
}