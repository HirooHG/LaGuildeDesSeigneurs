<?php

namespace App\Service;

use DateTime;
use App\Entity\Character;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CharacterType;
use LogicException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Cocur\Slugify\Slugify;

class CharacterService implements CharacterServiceInterface
{
  public function __construct(
    private EntityManagerInterface $em,
    private FormFactoryInterface $formFactoryInterface,
    private CharacterRepository $characterRepository
  ) {
  }
  public function create(string $data): Character
  {
    $character = new Character();

    $this->submit($character, CharacterType::class, $data);
    $character->setSlug((new Slugify())->slugify($character->getName()));
    $character->setCreation(new DateTime());
    $character->setModification(new DateTime());
    $character->setIdentifier(hash('sha1', uniqid()));
    $this->isEntityFilled($character);

    $this->em->persist($character);
    $this->em->flush();

    return $character;
  }

  public function update(Character $character, string $data): Character
  {
    $this->submit($character, CharacterType::class, $data);
    $character->setSlug((new Slugify())->slugify($character->getName()));
    $character->setModification(new DateTime());
    $this->isEntityFilled($character);

    $this->em->persist($character);
    $this->em->flush();

    return $character;
  }

  public function delete(Character $character): void
  {
    $this->em->remove($character);
    $this->em->flush();
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
  // Submits the form
  public function submit(Character $character, $formName, $data)
  {
    $dataArray = is_array($data) ? $data : json_decode($data, true);
    // Bad array
    if (null !== $data && !is_array($dataArray)) {
      throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);
    }
    // Submits form
    $form = $this->formFactoryInterface->create($formName, $character, ['csrf_protection' => false]);
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
  public function isEntityFilled(Character $character)
  {
    if (
      null === $character->getKind() ||
      null === $character->getName() ||
      null === $character->getSurname() ||
      null === $character->getSlug() ||
      null === $character->getIdentifier() ||
      null === $character->getCreation() ||
      null === $character->getModification()
    ) {
      $errorMsg = 'Missing data for Entity -> ' . json_encode($character->toArray());
      throw new UnprocessableEntityHttpException($errorMsg);
    }
  }
}
