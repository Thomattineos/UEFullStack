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
    private $available;

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

    public function getOpeningHours(): ?\DateTime
    {
        return $this->openingHours;
    }

    public function setOpeningHours(\DateTime $openingHours): self
    {
        $this->openingHours = $openingHours;

        return $this;
    }

    public function getClosingHours(): ?\DateTime
    {
        return $this->closingHours;
    }

    public function setClosingHours(\DateTime $closingHours): self
    {
        $this->closingHours = $closingHours;

        return $this;
    }

    /**
     * @return mixed
     */
    public function isAvailable()
    {
        return $this->available;
    }

    /**
     * @param mixed $available
     */
    public function setAvailable($available): void
    {
        $this->available = $available;
    }

}
