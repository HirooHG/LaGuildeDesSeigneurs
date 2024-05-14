<?php

namespace App\Listener;

use App\Event\BuildingEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BuildingListener implements EventSubscriberInterface
{
  public static function getSubscribedEvents(): array
  {
    return array(
      BuildingEvent::BUILDING_CREATED => 'buildingCreated',
      BuildingEvent::BUILDING_CREATED_POST_DATABASE => 'buildingCreatedPostDatabase',
    );
  }
  public function buildingCreated($event)
  {
    // TODO
  }

  public function buildingUpdated($event)
  {
    // TODO
  }

  public function buildingCreatedPostDatabase($event)
  {
    // TODO
  }
}
