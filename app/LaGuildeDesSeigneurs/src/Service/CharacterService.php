<?php

namespace App\Service;

use DateTime;
use App\Entity\Character;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;

class CharacterService implements CharacterServiceInterface
{
  public function __construct(
    private EntityManagerInterface $em,
    private CharacterRepository $characterRepository
  ) {
  }
  public function create(): Character
  {
    $character = new Character();

    $character->setKind('Dame');
    $character->setName('Maeglin');
    $character->setSlug('maeglin');
    $character->setSurname('Oeil vif');
    $character->setCaste('Archer');
    $character->setKnowledge('Nombres');
    $character->setIntelligence(120);
    $character->setStrength(120);
    $character->setImage('/dames/maeglin.webp');
    $character->setCreation(new DateTime());
    $character->setIdentifier(hash('sha1', uniqid()));

    $this->em->persist($character);
    $this->em->flush();

    return $character;
  }

  public function findAll(): array
  {
    $charactersFinal = array();
    $characters = $this->characterRepository->findAll();
    foreach ($characters as $character) {
      $charactersFinal[] = $character->toArray();
    }
    return $charactersFinal;
  }
}
