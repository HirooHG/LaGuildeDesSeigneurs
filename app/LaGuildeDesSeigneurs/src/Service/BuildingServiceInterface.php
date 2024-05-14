<?php

namespace App\Service;

use App\Entity\Building;

interface BuildingServiceInterface
{
  public function create(string $data);
  public function isEntityFilled(Building $building);
  public function submit(Building $building, $formName, $data);
  public function findAll();
  public function update(Building $building, string $data);
  public function delete(Building $building);
  public function serializeJson($object);
}
