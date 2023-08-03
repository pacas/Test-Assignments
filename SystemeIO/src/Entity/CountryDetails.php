<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CountryInfoRepository;

/**
 * @ORM\Entity(repositoryClass=CountryInfoRepository::class)
 */
class CountryDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $name;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private float $taxPercent;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $taxMask;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTaxPercent(): float
    {
        return $this->taxPercent;
    }

    public function getTaxMask(): string
    {
        return $this->taxMask;
    }


}