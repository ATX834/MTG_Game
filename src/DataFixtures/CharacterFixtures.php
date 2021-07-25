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
            ->addSpell($this->getReference('Fiery Confluence'))
            ->addColor($this->getReference('RED'))
            ->setToughness(2)
            ->setHealthPoint(17)
            ->setSpeed(7)
            ->setIsABonusCharacter(false)
            ->setUltimateText("Unleash your wrath on your enemy ! Victory is nearly yours");

        $gideon = new Character();

        $gideon
            ->setName('Gideon')
            ->setImage('/image/characters/gideon.png')
            ->addSpell($this->getReference('Adamant Will'))
            ->addSpell($this->getReference('Revitalize'))
            ->addColor($this->getReference('WHITE'))
            ->setPower(6)
            ->setToughness(5)
            ->setHealthPoint(25)
            ->setSpeed(2)
            ->setIsABonusCharacter(false)
            ->setUltimateText("Become incredibly powerful and overcome your enemy !");

        
        $audrey = new Character();

        $audrey
            ->setName('Audrey')
            ->setImage('/image/characters/audrey.png')
            ->addSpell($this->getReference('Sniper'))
            ->addSpell($this->getReference('Pas partir avant 17h'))
            ->addColor($this->getReference('RED'))
            ->setPower(7)
            ->setToughness(3)
            ->setHealthPoint(20)
            ->setSpeed(8)
            ->setIsABonusCharacter(true)
            ->setUltimateText("Dis moi, t'aurais pas oublié de signer ? ");

        $vincent = new Character();

        $vincent
            ->setName('Vincent')
            ->setImage('/image/characters/vincent.png')
            ->addSpell($this->getReference('Grim ambrée'))
            ->addSpell($this->getReference('Remote'))
            ->addColor($this->getReference('WHITE'))
            ->setPower(9)
            ->setToughness(4)
            ->setHealthPoint(25)
            ->setSpeed(3)
            ->setIsABonusCharacter(true)
            ->setUltimateText("Gueule de bois powaaa !!");

        $geoffroy = new Character();

        $geoffroy
            ->setName('Geoffroy')
            ->setImage('/image/characters/geoffroy.png')
            ->addSpell($this->getReference('Sniper'))
            ->addSpell($this->getReference('Remote'))
            ->addColor($this->getReference('WHITE'))
            ->addColor($this->getReference('RED'))
            ->setPower(7)
            ->setToughness(6)
            ->setHealthPoint(19)
            ->setSpeed(5)
            ->setIsABonusCharacter(true)
            ->setUltimateText("PETAGE DE GENOUX GARANTI");
        
        $manager->persist($chandra);
        $manager->persist($gideon);
        $manager->persist($audrey);
        $manager->persist($vincent);
        $manager->persist($geoffroy);

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
