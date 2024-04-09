<?php

namespace App\DataFixtures;

use Cocur\Slugify\Slugify;
use App\Entity\Character;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
  private $slugify;
  public function __construct()
  {
    $this->slugify = new Slugify();
  }
  public function load(ObjectManager $manager): void
  {
    // Creates All the Characters from json
    $characters = json_decode(file_get_contents('https://la-guilde-des-seigneurs.com/json/characters.json'), true);
    foreach ($characters as $characterData) {
      $manager->persist($this->setCharacter($characterData));
    }

    // $totalCharacters = 20;
    //
    // for ($i = 1; $i <= $totalCharacters; $i++) {
    //   $character = new Character();
    //   $character->setKind(rand(0, 1) ? 'Dame' : 'Seigneur');
    //   $character->setName('Maeglin' . $i);
    //   $character->setSlug('maeglin' . $i);
    //   $character->setSurname('Oeil vif');
    //   $character->setCaste('Archer');
    //   $character->setKnowledge('Nombres');
    //   $character->setIntelligence(mt_rand(100, 200));
    //   $character->setStrength(mt_rand(100, 200));
    //   $character->setIdentifier(hash('sha1', uniqid()));
    //   $character->setImage('/' . strtolower($character->getKind()) . 's/' . strtolower($character->getKind()) . '.webp');
    //   $character->setCreation(new \DateTime());
    //
    //   $manager->persist($character);
    // }

    $manager->flush();
  }
  // Sets the Character with its data
  public function setCharacter(array $characterData): Character
  {
    $character = new Character();
    foreach ($characterData as $key => $value) {
      $method = 'set' . ucfirst($key); // Construit le nom de la méthode
      if (method_exists($character, $method)) { // Si la méthode existe
        $character->$method($value ?? null); // Appelle la méthode
      }
    }
    $character->setSlug($this->slugify->slugify($characterData['name']));
    $character->setIdentifier(hash('sha1', uniqid()));
    $character->setCreation(new \DateTime());
    return $character;
  }
}
