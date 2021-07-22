<?php

namespace App\DataFixtures;

use App\Entity\Spell;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SpellFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $lightingBolt = new Spell();
        $lightingBolt
            ->setName('Lightning Bolt')
            ->setImage('/image/spells/lightningBolt.png')
            ->setType('Instant')
            ->setManaCost(2);
        $this->addReference('Lightning Bolt', $lightingBolt);
        
        $chandraEmbercat = new Spell();
        $chandraEmbercat
            ->setName('Chandra Embercat')
            ->setImage('/image/spells/chandraEmbercat.png')
            ->setType('Creature')
            ->setManaCost(2)
            ->setPower(1)
            ->setToughness(1)
            ->setSpeed(7);
        $this->addReference('Chandra Embercat', $chandraEmbercat);
        
        $pathToExile = new Spell();
        $pathToExile
            ->setName('Path To Exile')
            ->setImage('/image/spells/pathToExile.png')
            ->setType('Instant')
            ->setManaCost(2);
        $this->addReference('Path To Exile', $pathToExile);
        
        $revitalize = new Spell();
        $revitalize
            ->setName('Revitalize')
            ->setImage('/image/spells/revitalize.png')
            ->setType('Instant')
            ->setManaCost(3);
        $this->addReference('Revitalize', $revitalize);
        

        $manager->persist($lightingBolt);
        $manager->persist($chandraEmbercat);
        $manager->persist($pathToExile);

        $manager->flush();
    }
}
