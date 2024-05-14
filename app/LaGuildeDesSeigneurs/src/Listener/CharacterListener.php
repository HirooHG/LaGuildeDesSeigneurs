<?php

namespace App\Listener;

use App\Event\CharacterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CharacterListener implements EventSubscriberInterface
{
  public static function getSubscribedEvents(): array
  {
    // Événements que l'on souhaite écouter
    return array(
      CharacterEvent::CHARACTER_CREATED => 'characterCreated',
      CharacterEvent::CHARACTER_CREATED_POST_DATABASE => 'characterCreatedPostDatabase',
      CharacterEvent::CHARACTER_UPDATED => 'characterCreated',
    );
  }
  // Méthode appelée lorsque l'objet est créé
  public function characterCreated($event)
  {
    // Réception de l'objet Character avec le getter
    $character = $event->getCharacter();
    // Modification de l'objet
    $character->setIntelligence(250);

    if ("Dame" === $character->getKind()) {
      $character->setStrength($character->getStrength() + 5);
    } elseif ("Tourmenteuse" === $character->getKind()) {
      $character->setStrength($character->getStrength() - 5);
    }
  }

  public function characterUpdated($event)
  {
    // TODO
  }

  public function characterCreatedPostDatabase($event)
  {
    // TODO
  }
}
