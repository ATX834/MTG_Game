<?php

namespace App\DataFixtures;

use App\Entity\Character;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CharacterFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $chandra = new Character();

        $chandra
            ->setName('Chandra')
            ->setImage('/image/characters/chandra.png')
            ->setPower(5)
            ->addSpell($this->getReference('Lightning Bolt'))
            ->addSpell($this->getReference('Chandra Embercat'))
            ->addColor($this->getReference('RED'))
            ->setToughness(2)
            ->setHealthPoint(17)
            ->setSpeed(7);

        $gideon = new Character();

        $gideon
            ->setName('Gideon')
            ->setImage('/image/characters/gideon.png')
            ->addSpell($this->getReference('Path To Exile'))
            ->addSpell($this->getReference('Revitalize'))
            ->addColor($this->getReference('WHITE'))
            ->setPower(6)
            ->setToughness(5)
            ->setHealthPoint(25)
            ->setSpeed(2);
        

        $manager->persist($chandra);
        $manager->persist($gideon);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          ColorFixtures::class,
          SpellFixtures::class,
        ];
    }
}
