<?php

namespace App\DataFixtures;

use App\Entity\Building;
use Cocur\Slugify\Slugify;
use App\Entity\Character;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
  private $slugify;
  public function __construct(private UserPasswordHasherInterface $hasher)
  {
    $this->slugify = new Slugify();
  }
  public function load(ObjectManager $manager): void
  {
    $users = $this->loadUsers($manager);
    $this->loadCharacters($manager, $users);
    $this->loadBuildings($manager);
  }

  public function loadUsers(ObjectManager $manager): array
  {
    // Creates Users
    $emails = [
      'contact@example.com',
      'info@example.com',
      'email@example.com',
    ];
    $users = [];
    foreach ($emails as $email) {
      $user = new User();
      $user->setEmail($email);
      $user->setPassword($this->hasher->hashPassword($user, 'StrongPassword*'));
      $user->setCreation(new \DateTime());
      $user->setModification(new \DateTime());
      // On définit seulement cet utilisateur comme admin
      if ('contact@example.com' === $email) {
        $user->setRoles(['ROLE_ADMIN']);
      }
      $manager->persist($user);
      $users[] = $user;
    }

    $manager->flush();
    return $users;
  }

  public function loadBuildings(ObjectManager $manager): void
  {
    //load from json
    $buildings = json_decode(file_get_contents('https://la-guilde-des-seigneurs.com/json/buildings.json'), true);
    foreach ($buildings as $buildingData) {
      $manager->persist($this->setBuilding($buildingData));
    }

    $manager->flush();
  }

  public function loadCharacters(ObjectManager $manager, array $users): void
  {
    // Creates All the Characters from json
    $characters = json_decode(file_get_contents('https://la-guilde-des-seigneurs.com/json/characters.json'), true);
    foreach ($characters as $characterData) {
      $character = $this->setCharacter($characterData);
      $character->setUser($users[array_rand($users)]);
      $manager->persist($character);
    }

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
    $character->setModification(new \DateTime());
    return $character;
  }

  // Sets the Building with its data
  public function setBuilding(array $buildingData): Building
  {
    $building = new Building();
    foreach ($buildingData as $key => $value) {
      $method = 'set' . ucfirst($key); // Construit le nom de la méthode
      if (method_exists($building, $method)) { // Si la méthode existe
        $building->$method($value ?? null); // Appelle la méthode
      }
    }
    $building->setSlug($this->slugify->slugify($buildingData['name']));
    $building->setIdentifier(hash('sha1', uniqid()));
    $building->setCreation(new \DateTime());
    $building->setModification(new \DateTime());
    $building->setRate(0);
    return $building;
  }
}
