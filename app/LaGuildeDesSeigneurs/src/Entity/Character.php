<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $name = "Fëanturi";

    #[ORM\Column(length: 50)]
    private ?string $surname = "Fascination";

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $caste = "ElfeNoir";

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $knowledge = "Tourmenteuse";

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $intelligence = 160;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $strength = 1600;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 20)]
    private ?string $slug = null;

    #[ORM\Column(length: 20)]
    private ?string $kind = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creation = null;

    public function toArray()
    {
        return get_object_vars($this);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getCaste(): ?string
    {
        return $this->caste;
    }

    public function setCaste(?string $caste): static
    {
        $this->caste = $caste;

        return $this;
    }

    public function getKnowledge(): ?string
    {
        return $this->knowledge;
    }

    public function setKnowledge(?string $knowledge): static
    {
        $this->knowledge = $knowledge;

        return $this;
    }

    public function getIntelligence(): ?int
    {
        return $this->intelligence;
    }

    public function setIntelligence(?int $intelligence): static
    {
        $this->intelligence = $intelligence;

        return $this;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(?int $strength): static
    {
        $this->strength = $strength;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getKind(): ?string
    {
        return $this->kind;
    }

    public function setKind(string $kind): static
    {
        $this->kind = $kind;

        return $this;
    }

    public function getCreation(): ?\DateTimeInterface
    {
        return $this->creation;
    }

    public function setCreation(\DateTimeInterface $creation): static
    {
        $this->creation = $creation;

        return $this;
    }
}
