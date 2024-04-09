<?php

namespace App\Entity;

use App\Repository\BuildingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuildingRepository::class)]
#[ORM\Table(name: '`building`')]
class Building
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id;

  #[ORM\Column(length: 20)]
  private ?string $name = "Lenora";

  #[ORM\Column(length: 20)]
  private ?string $caste = "Guerrier";

  #[ORM\Column]
  private ?int $strength = 1000;

  #[ORM\Column(length: 50, nullable: true)]
  private ?string $image = "/buildings/lenora.webp";

  #[ORM\Column(length: 20)]
  private ?string $slug = "chateau-lenora";

  #[ORM\Column]
  private ?int $rate = null;

  #[ORM\Column(length: 50)]
  private ?string $identifier = null;

  #[ORM\Column(type: Types::DATETIME_MUTABLE)]
  private ?\DateTimeInterface $creation = null;

  #[ORM\Column(type: Types::DATETIME_MUTABLE)]
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

  public function getCaste(): ?string
  {
    return $this->caste;
  }

  public function setCaste(string $caste): static
  {
    $this->caste = $caste;

    return $this;
  }

  public function getStrength(): ?int
  {
    return $this->strength;
  }

  public function setStrength(int $strength): static
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

  public function getRate(): ?int
  {
      return $this->rate;
  }

  public function setRate(int $rate): static
  {
      $this->rate = $rate;

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

  public function getCreation(): ?\DateTimeInterface
  {
      return $this->creation;
  }

  public function setCreation(\DateTimeInterface $creation): static
  {
      $this->creation = $creation;

      return $this;
  }

  public function getModification(): ?\DateTimeInterface
  {
      return $this->modification;
  }

  public function setModification(\DateTimeInterface $modification): static
  {
      $this->modification = $modification;

      return $this;
  }
}
