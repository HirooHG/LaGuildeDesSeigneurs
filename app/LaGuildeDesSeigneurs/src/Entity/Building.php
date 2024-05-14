<?php

namespace App\Entity;

use App\Repository\BuildingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BuildingRepository::class)]
#[ORM\Table(name: '`building`')]
class Building
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id;

  #[ORM\Column(length: 20)]
  #[Assert\NotNull]
  #[Assert\NotBlank]
  #[Assert\Length(
    min: 3,
    max: 20,
  )]
  private ?string $name = "Lenora";

  #[ORM\Column(length: 20)]
  #[Assert\NotNull]
  #[Assert\NotBlank]
  #[Assert\Length(
    min: 3,
    max: 20,
  )]
  private ?string $caste = "Guerrier";

  #[ORM\Column]
  #[Assert\PositiveOrZero]
  private ?int $strength = 1000;

  #[ORM\Column(length: 50, nullable: true)]
  #[Assert\Length(
    min: 5,
    max: 50,
  )]
  private ?string $image = "/buildings/lenora.webp";

  #[ORM\Column(length: 20)]
  #[Assert\NotNull]
  #[Assert\NotBlank]
  #[Assert\Length(
    min: 3,
    max: 20,
  )]
  private ?string $slug = "chateau-lenora";

  #[ORM\Column]
  private ?int $rate = null;

  #[ORM\Column(length: 50)]
  #[Assert\NotNull]
  #[Assert\NotBlank]
  #[Assert\Length(
    min: 40,
    max: 40,
  )]
  private ?string $identifier = null;

  #[ORM\Column(type: Types::DATETIME_MUTABLE)]
  private ?\DateTimeInterface $creation = null;

  #[ORM\Column(type: Types::DATETIME_MUTABLE)]
  private ?\DateTimeInterface $modification = null;

  /**
   * @var Collection<int, Character>
   */
  #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'building')]
  private Collection $characters;

  public function __construct()
  {
    $this->characters = new ArrayCollection();
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

  /**
   * @return Collection<int, Character>
   */
  public function getCharacters(): Collection
  {
    return $this->characters;
  }

  public function addCharacter(Character $character): static
  {
    if (!$this->characters->contains($character)) {
      $this->characters->add($character);
      $character->setBuilding($this);
    }

    return $this;
  }

  public function removeCharacter(Character $character): static
  {
    if ($this->characters->removeElement($character)) {
      // set the owning side to null (unless already changed)
      if ($character->getBuilding() === $this) {
        $character->setBuilding(null);
      }
    }

    return $this;
  }
}
