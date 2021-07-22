<?php

namespace App\DataFixtures;

use App\Entity\Color;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ColorFixtures extends Fixture
{
    public const NAMES = [
        'WHITE',
        'BLACK',
        'BLUE',
        'RED',
        'GREEN'
    ];

    public function load(ObjectManager $manager)
    {
        foreach(self::NAMES as $name)
        {
            $color = new Color();
            $color->setName($name);
            $this->addReference($name, $color);

            $manager->persist($color);
        }

        $manager->flush();
    }
}
