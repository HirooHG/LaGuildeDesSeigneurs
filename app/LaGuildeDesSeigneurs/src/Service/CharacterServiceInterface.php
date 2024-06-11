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
    public function findAll();
    public function findAllPaginated($query);
    public function update(Character $character, string $data);
    public function delete(Character $character);
    // Serializes the object(s)
    public function serializeJson($object);
    //set links
    public function setLinks($character);
    // Gets random images
    public function getImages(int $number, ?string $kind = null): array;
    // Gets random images by kind
    public function getImagesKind(string $kind, int $number): array;
    // find all with intelligence
    public function findAllWithIntelligence(array $characters, int $queryIntelligence);
}
