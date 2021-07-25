<?php

namespace App\Ultimate;

use App\Entity\Fighter;
use Doctrine\ORM\EntityManagerInterface;

class ChandraUltimate
{
    public const MANA_COST = 5;

    public function execute(Fighter $chandra, Fighter $target)
    {
        $target->setHealthPoint($target->getHealthPoint() - 8);
        $target->setMaxHealthPoint($target->getMaxHealthPoint() - 2);
        $target->setPower($target->getPower() - 2);

        $chandra->setManaBase($chandra->getManaBase() - self::MANA_COST);
        
        $this->em->flush();
    }
}