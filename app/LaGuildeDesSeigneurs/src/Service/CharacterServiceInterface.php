<?php

namespace App\Service;

use App\Entity\Character;

interface CharacterServiceInterface
{
  // Creates the character
  public function create(string $data);
  // Checks if the entity has been well filled
  public function isEntityFilled(Character $character);
  // Submits the data to hydrate the object
  public function submit(Character $character, $formName, $data);
  public function findAllJson();
  public function update(Character $character, string $data);
  public function delete(Character $character);
  // Serializes the object(s)
  public function serializeJson($object);
}
