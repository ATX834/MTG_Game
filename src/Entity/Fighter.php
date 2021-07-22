<?php

namespace App\Entity;

use App\Repository\FighterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FighterRepository::class)
 */
class Fighter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Character::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $personnage;

    /**
     * @ORM\Column(type="integer")
     */
    private $power;

    /**
     * @ORM\Column(type="integer")
     */
    private $toughness;

    /**
     * @ORM\Column(type="integer")
     */
    private $healthPoint;

    /**
     * @ORM\Column(type="integer")
     */
    private $manaBase;

    /**
     * @ORM\Column(type="integer")
     */
    private $speed;

    /**
     * @ORM\ManyToMany(targetEntity=Spell::class, inversedBy="fighters")
     */
    private $spells;

    /**
     * @ORM\ManyToMany(targetEntity=Color::class, inversedBy="fighters")
     */
    private $colors;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $player;

    public function __construct()
    {
        $this->spells = new ArrayCollection();
        $this->colors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersonnage(): ?Character
    {
        return $this->personnage;
    }

    public function setPersonnage(?Character $personnage): self
    {
        $this->personnage = $personnage;

        return $this;
    }

    public function getPower(): ?int
    {
        return $this->power;
    }

    public function setPower(int $power): self
    {
        $this->power = $power;

        return $this;
    }

    public function getToughness(): ?int
    {
        return $this->toughness;
    }

    public function setToughness(int $toughness): self
    {
        $this->toughness = $toughness;

        return $this;
    }

    public function getHealthPoint(): ?int
    {
        return $this->healthPoint;
    }

    public function setHealthPoint(int $healthPoint): self
    {
        $this->healthPoint = $healthPoint;

        return $this;
    }

    public function getManaBase(): ?int
    {
        return $this->manaBase;
    }

    public function setManaBase(int $manaBase): self
    {
        $this->manaBase = $manaBase;

        return $this;
    }

    public function getSpeed(): ?int
    {
        return $this->speed;
    }

    public function setSpeed(int $speed): self
    {
        $this->speed = $speed;

        return $this;
    }

    /**
     * @return Collection|Spell[]
     */
    public function getSpells(): Collection
    {
        return $this->spells;
    }

    public function addSpell(Spell $spell): self
    {
        if (!$this->spells->contains($spell)) {
            $this->spells[] = $spell;
        }

        return $this;
    }

    public function removeSpell(Spell $spell): self
    {
        $this->spells->removeElement($spell);

        return $this;
    }

    /**
     * @return Collection|Color[]
     */
    public function getColors(): Collection
    {
        return $this->colors;
    }

    public function addColor(Color $color): self
    {
        if (!$this->colors->contains($color)) {
            $this->colors[] = $color;
        }

        return $this;
    }

    public function removeColor(Color $color): self
    {
        $this->colors->removeElement($color);

        return $this;
    }

    public function getPlayer(): ?string
    {
        return $this->player;
    }

    public function setPlayer(string $player): self
    {
        $this->player = $player;

        return $this;
    }
}
