<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{
  private static $userId;
  private $client;

  private $content;
  private static $identifier;

  public function setUp(): void
  {
    $this->client = static::createClient();

    $userRepository = static::getContainer()->get(UserRepository::class);
    $testUser = $userRepository->findOneByEmail('contact@example.com');
    self::$userId = $testUser->getId();
    $this->client->loginUser($testUser);
  }

  ###
  # passing tests
  public function testCreate()
  {
    $userId = self::$userId;
    $this->client->request(
      'POST',
      '/characters',
      array(), // Parameters
      array(), // Files
      array('CONTENT_TYPE' => 'application/json'), // Server
      <<<JSON
        {
          "kind": "Dame",
          "name": "Maeglin",
          "surname": "Oeil vif",
          "caste": "Archer",
          "knowledge": "Nombres",
          "intelligence": 120,
          "strength": 120,
          "image": "/dames/maeglin.webp",
          "userId": "{$userId}"
        }
      JSON
    );
    $this->assertResponseCode(201);
    $this->assertJsonResponse();
    $this->defineIdentifier();
    $this->assertIdentifier();
  }
  public function testDisplay(): void
  {
    $this->client->request('GET', '/characters/' . self::$identifier);
    $this->assertResponseCode(200);
    $this->assertJsonResponse();
    $this->assertIdentifier();
  }
  public function testIndex()
  {
    $this->client->request('GET', '/characters');
    $this->assertResponseCode(200);
    $this->assertJsonResponse();

    $this->client->request('GET', '/characters?page=1');
    $this->assertResponseCode(200);
    $this->assertJsonResponse();

    $this->client->request('GET', '/characters?page=1&size=1');
    $this->assertResponseCode(200);
    $this->assertJsonResponse();

    $this->client->request('GET', '/characters?size=1');
    $this->assertResponseCode(200);
    $this->assertJsonResponse();


    $this->client->request('GET', '/characters?intelligence=190');
    $this->assertResponseCode(200);
    $this->assertJsonResponse();
  }
  public function testUpdate()
  {
    //Tests with partial data array
    $this->client->request(
      'PUT',
      '/characters/' . self::$identifier,
      array(), // Parameters
      array(), // Files
      array('CONTENT_TYPE' => 'application/json'), // Server
      <<<JSON
        {
          "kind": "Seigneur",
          "name": "Gorthol"
        }
      JSON
    );
    $this->assertResponseCode(204);
  }
  public function testDelete()
  {
    $this->client->request('DELETE', '/characters/' . self::$identifier);
    $this->assertResponseCode(204);
  }

  // Tests images
  public function testImages()
  {
    //Tests without kind
    $this->client->request('GET', '/characters/images');
    $this->assertJsonResponse();
    $this->client->request('GET', '/characters/images/3');
    $this->assertJsonResponse();

    //Tests with kind
    $this->client->request('GET', '/characters/images/dames');
    $this->assertJsonResponse();
    $this->client->request('GET', '/characters/images/dames/3');
    $this->assertJsonResponse();
    $this->client->request('GET', '/characters/images/seigneurs/3');
    $this->assertJsonResponse();
    $this->client->request('GET', '/characters/images/tourmenteurs/3');
    $this->assertJsonResponse();
    $this->client->request('GET', '/characters/images/tourmenteuses/3');
    $this->assertJsonResponse();
  }

  ###
  # failing tests
  public function testDeleteInexistingIdentifier()
  {
    $this->client->request('DELETE', '/characters/8f74f20597c5cf99dd42cd31331b7e6e2aeerror');
    $this->assertResponseCode(404);
  }
  public function testBadIdentifier()
  {
    $this->client->request('GET', '/characters/badIdentifier');
    $this->assertResponseCode(404);
  }
  public function testInexistingIdentifier()
  {
    $this->client->request('GET', '/characters/8f74f20597c5cf99dd42cd31331b7e6e2aeerror');
    $this->assertResponseCode(404);
  }

  // private fn
  public function assertJsonResponse(): void
  {
    $response = $this->client->getResponse();
    $this->content = json_decode($response->getContent(), true, 50);
    $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
  }

  private function defineIdentifier()
  {
    self::$identifier = $this->content['identifier'];
  }
  private function assertIdentifier()
  {
    $this->assertEquals(self::$identifier, $this->content['identifier']);
  }
  public function assertResponseCode(int $code)
  {
    $response = $this->client->getResponse();
    $this->assertEquals($code, $response->getStatusCode());
  }
}
