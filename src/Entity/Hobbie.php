<?php

namespace App\Entity;

use App\Repository\HobbieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HobbieRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Hobbie
{
    use TimeStampTraits;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $designation = null;

    #[ORM\Column(length: 255)]
    private ?string $profile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setProfile(string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function __toString(): string
    {
        return $this->designation;
    }
}
