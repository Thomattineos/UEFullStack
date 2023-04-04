<?php

namespace App\Entity;

use App\Repository\ShopRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShopRepository::class)
 */
class Shop
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="time")
     */
    private $openingHours;

    /**
     * @ORM\Column(type="time")
     */
    private $closingHours;

    /**
     * @ORM\Column(type="boolean")
     */
    private $leave;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOpeningHours(): ?\DateTimeInterface
    {
        return $this->openingHours;
    }

    public function setOpeningHours(\DateTimeInterface $openingHours): self
    {
        $this->openingHours = $openingHours;

        return $this;
    }

    public function getClosingHours(): ?\DateTimeInterface
    {
        return $this->closingHours;
    }

    public function setClosingHours(\DateTimeInterface $closingHours): self
    {
        $this->closingHours = $closingHours;

        return $this;
    }

    public function isLeave(): ?bool
    {
        return $this->leave;
    }

    public function setLeave(bool $leave): self
    {
        $this->leave = $leave;

        return $this;
    }
}
