<?php

namespace App\Entity;

use App\Repository\ShopRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Regex(
     *     pattern="/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/",
     *     message="Le format de l'heure d'ouverture doit être HH:mm"
     * )
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


    /**
     * @ORM\Column(type="date")
     */
    private $creationDate;


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

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param mixed $creationDate
     */
    public function setCreationDate($creationDate): void
    {
        $this->creationDate = $creationDate;
    }
}
