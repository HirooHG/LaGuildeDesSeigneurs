<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\Table(name: '`character`')]
class Character
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 40)]
  #[Assert\NotNull]
  #[Assert\NotBlank]
  #[Assert\Length(
    min: 40, // si on veut une taille fixe il suffit
    max: 40, // de mettre la même valeur pour min et max
  )]
  private ?string $identifier = null;

  #[ORM\Column(length: 20)]
  #[Assert\NotNull] // Pour que ce ne soit pas null
  #[Assert\NotBlank] // Pour que ce ne soit pas blanc
  #[Assert\Length( //Définit une taille mini et maxi
    min: 3,
    max: 20, // Messages pour customisation, sinon on peut les supprimer
  )]
  private ?string $name = "Fëanturi";

  #[ORM\Column(length: 50)]
  #[Assert\NotNull]
  #[Assert\NotBlank]
  #[Assert\Length(
    min: 3,
    max: 50, // 50 caractères
  )]
  private ?string $surname = "Fascination";

  #[ORM\Column(length: 20, nullable: true)]
  // Pas de NotNull et NotBlank puisque le champ est nullable
  #[Assert\Length(
    min: 3,
    max: 20,
  )]
  private ?string $caste = "ElfeNoir";

  #[ORM\Column(length: 20, nullable: true)]
  #[Assert\Length(
    min: 3,
    max: 20,
  )]
  private ?string $knowledge = "Arts";

  #[ORM\Column(type: Types::SMALLINT, nullable: true)]
  #[Assert\PositiveOrZero] // OU #[Assert\Positive] si on ne veut pas de 0
  private ?int $intelligence = 160;

  #[ORM\Column(type: Types::SMALLINT, nullable: true)]
  #[Assert\PositiveOrZero]
  private ?int $strength = 1600;

  #[ORM\Column(length: 50, nullable: true)]
  #[Assert\Length(
    min: 5,
    max: 50,
  )]
  private ?string $image = "/dame/bruh.webp";

  #[ORM\Column(length: 20)]
  #[Assert\NotNull]
  #[Assert\NotBlank]
  #[Assert\Length(
    min: 3,
    max: 20,
  )]
  private ?string $slug = "TourmenteursDuChaos";

  #[ORM\Column(length: 20)]
  #[Assert\NotNull]
  #[Assert\NotBlank]
  #[Assert\Length(
    min: 3,
    max: 20,
  )]
  private ?string $kind = "Tourmenteuse";

  #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
  private ?\DateTimeInterface $creation = null;

  #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
  private ?\DateTimeInterface $modification = null;

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

  public function getIdentifier(): ?string
  {
    return $this->identifier;
  }

  public function setIdentifier(string $identifier): static
  {
    $this->identifier = $identifier;

    return $this;
  }

  public function getModification(): ?\DateTimeInterface
  {
    return $this->modification;
  }

  public function setModification(?\DateTimeInterface $modification): static
  {
    $this->modification = $modification;

    return $this;
  }
}
