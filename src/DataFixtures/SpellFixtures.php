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
            ->setManaCost(2)
            ->setText("Deals 5 damages to your opponent.");
        $this->addReference('Lightning Bolt', $lightingBolt);
        
        $chandraEmbercat = new Spell();
        $chandraEmbercat
            ->setName('Chandra Embercat')
            ->setImage('/image/spells/chandraEmbercat.png')
            ->setType('Creature')
            ->setManaCost(2)
            ->setPower(1)
            ->setToughness(1)
            ->setSpeed(7)
            ->setText("Casts the famous Chandra Embercat.");
        $this->addReference('Chandra Embercat', $chandraEmbercat);
        
        $pathToExile = new Spell();
        $pathToExile
            ->setName('Path To Exile')
            ->setImage('/image/spells/pathToExile.png')
            ->setType('Instant')
            ->setManaCost(2)
            ->setText('Destroys a casted creature.');
        $this->addReference('Path To Exile', $pathToExile);
        
        $adamantWill = new Spell();
        $adamantWill
            ->setName('Adamant Will')
            ->setImage('/image/spells/adamantWill.png')
            ->setType('Instant')
            ->setManaCost(3)
            ->setText('Makes you damage-free for this turn.');
        $this->addReference('Adamant Will', $adamantWill);
  
        $revitalize = new Spell();
        $revitalize
            ->setName('Revitalize')
            ->setImage('/image/spells/revitalize.png')
            ->setType('Instant')
            ->setManaCost(3)
            ->setText("Gives you 3 HPs and buffs one random stat.");
        $this->addReference('Revitalize', $revitalize);


        $fieryConfluence = new Spell();
        $fieryConfluence
            ->setName('Fiery Confluence')
            ->setImage('/image/spells/fieryConfluence.png')
            ->setType('Instant')
            ->setManaCost(4)
            ->setText("Burns the opponent for 2 damages, decrease opponent's toughness and increase your power.");
        $this->addReference('Fiery Confluence', $fieryConfluence);

        $sniper = new Spell();
        $sniper
            ->setName('Sniper')
            ->setImage('/image/spells/lightningBolt.png')
            ->setType('Instant')
            ->setManaCost(2)
            ->setText("Oh lé fote dortograf");
        $this->addReference('Sniper', $sniper);

        $remote = new Spell();
        $remote
            ->setName('Remote')
            ->setImage('/image/spells/adamantWill.png')
            ->setType('Instant')
            ->setManaCost(3)
            ->setText("T'es en remote, personne te fera chier");
        $this->addReference('Remote', $remote);
  
        $grimAmbree = new Spell();
        $grimAmbree
            ->setName('Grimm ambrée')
            ->setImage('/image/spells/revitalize.png')
            ->setType('Instant')
            ->setManaCost(3)
            ->setText("Y'a SOIF !");
        $this->addReference('Grim ambrée', $grimAmbree);


        $pasPartirAvant17h = new Spell();
        $pasPartirAvant17h
            ->setName('Pas partir avant 17h')
            ->setImage('/image/spells/fieryConfluence.png')
            ->setType('Instant')
            ->setManaCost(4)
            ->setText("T'as fait le fou, fallait pas partir avant 17h");
        $this->addReference('Pas partir avant 17h', $pasPartirAvant17h);
        

        $manager->persist($lightingBolt);
        $manager->persist($chandraEmbercat);
        $manager->persist($pathToExile);
        $manager->persist($revitalize);
        $manager->persist($adamantWill);
        $manager->persist($fieryConfluence);
        
        $manager->persist($sniper);
        $manager->persist($remote);
        $manager->persist($grimAmbree);
        $manager->persist($pasPartirAvant17h);
        

        $manager->flush();
    }
}
