<?php

namespace App\Service;

use App\Entity\Building;
use App\Repository\BuildingRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class BuildingService implements BuildingServiceInterface
{
  public function __construct(
    private BuildingRepository $buildingRepository,
    private FormFactoryInterface $formFactoryInterface,
    private EntityManagerInterface $entityManager
  ) {
  }

  public function create(string $data): Building
  {
    $building = new Building();

    $this->submit($building, Building::class, $data);
    $building->setSlug((new Slugify())->slugify($building->getName()));
    $building->setCreation(new \DateTime());
    $building->setModification(new \DateTime());
    $building->setIdentifier(hash('sha1', uniqid()));
    $this->isEntityFilled($building);

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

  public function update(Building $building, string $data): Building
  {
    $this->submit($building, Building::class, $data);
    $building->setSlug((new Slugify())->slugify($building->getName()));
    $building->setModification(new \DateTime());
    $this->isEntityFilled($building);

    $this->entityManager->persist($building);
    $this->entityManager->flush();

    return $building;
  }

  public function delete(Building $building): void
  {
    $this->entityManager->remove($building);
    $this->entityManager->flush();
  }

  public function submit(Building $building, $formName, $data)
  {
    $dataArray = is_array($data) ? $data : json_decode($data, true);
    // Bad array
    if (null !== $data && !is_array($dataArray)) {
      throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);
    }
    // Submits form
    $form = $this->formFactoryInterface->create($formName, $building, ['csrf_protection' => false]);
    $form->submit($dataArray, false); // With false, only submitted fields are validated
    // Gets errors
    $errors = $form->getErrors();
    foreach ($errors as $error) {
      $errorMsg = 'Error ' . get_class($error->getCause());
      $errorMsg .= ' --> ' . $error->getMessageTemplate();
      $errorMsg .= ' ' . json_encode($error->getMessageParameters());
      throw new LogicException($errorMsg);
    }
  }

  // Checks if the entity has been well filled
  public function isEntityFilled(Building $building)
  {
    if (
      null === $building->getName() ||
      null === $building->getSlug() ||
      null === $building->getIdentifier() ||
      null === $building->getCreation() ||
      null === $building->getModification()
    ) {
      $errorMsg = 'Missing data for Entity -> ' . json_encode($building->toArray());
      throw new UnprocessableEntityHttpException($errorMsg);
    }
  }
}
