<?php

namespace App\Event;

use App\Entity\Building;
use Symfony\Contracts\EventDispatcher\Event;

class BuildingEvent extends Event
{
  public const BUILDING_CREATED = 'app.building.created';
  public const BUILDING_CREATED_POST_DATABASE = 'app.building.created_post_database';
  public function __construct(
    protected Building $building
  ) {
  }
  public function getBuilding(): Building
  {
    return $this->building;
  }
}
