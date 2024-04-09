<?php

namespace App\Service;

use App\Entity\Building;

interface BuildingServiceInterface
{
  public function create();
  public function findAll();
  public function update(Building $building);
  public function delete(Building $building);
}
