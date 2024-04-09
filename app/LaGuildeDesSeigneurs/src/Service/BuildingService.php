<?php

namespace App\Service;

use App\Entity\Building;
use App\Repository\BuildingRepository;
use Doctrine\ORM\EntityManagerInterface;

class BuildingService implements BuildingServiceInterface
{
  private BuildingRepository $buildingRepository;
  private EntityManagerInterface $entityManager;

  public function __construct(BuildingRepository $buildingRepository, EntityManagerInterface $entityManager)
  {
    $this->buildingRepository = $buildingRepository;
    $this->entityManager = $entityManager;
  }

  public function create(): Building
  {
    $building = new Building();
    $building->setName('Antoneme');
    $building->setCaste('Chevalier');
    $building->setStrength(1000);
    $building->setImage('/buildings/antoleme.webp');
    $building->setSlug('chateau-antoleme');
    $building->setRate(3);
    $building->setCreation(new \DateTime());
    $building->setModification(new \DateTime());
    $building->setIdentifier(hash('sha1', uniqid()));

    $this->entityManager->persist($building);
    $this->entityManager->flush();

    return $building;
  }

  public function findAll(): array
  {

    $buildingsFinal = array();
    $buildings = $this->buildingRepository->findAll();
    foreach ($buildings as $building) {
      $buildingsFinal[] = $building->toArray();
    }
    return $buildingsFinal;
  }

  public function update(Building $building): Building
  {
    $building->setName('Silken');
    $building->setCaste('Archer');
    $building->setStrength(1200);
    $building->setImage('/buildings/silken.webp');
    $building->setSlug('chateau-silken');
    $building->setRate(3);
    $building->setModification(new \DateTime());

    $this->entityManager->persist($building);
    $this->entityManager->flush();

    return $building;
  }

  public function delete(Building $building): void
  {
    $this->entityManager->remove($building);
    $this->entityManager->flush();
  }
}
