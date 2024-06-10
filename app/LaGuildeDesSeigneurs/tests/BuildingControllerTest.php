<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BuildingControllerTest extends WebTestCase
{
  public static $userId;
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
    $this->client->request(
      'POST',
      '/buildings',
      array(), // Parameters
      array(), // Files
      array('CONTENT_TYPE' => 'application/json'), // Server
      <<<JSON
        {
          "name": "Lenora",
          "caste": "Guerrier",
          "strength": 1000,
          "image": "/buildings/lenora.webp",
          "slug": "Chateau Lenora",
          "rate": 4
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
    $this->client->request('GET', '/buildings/' . self::$identifier);
    $this->assertResponseCode(200);
    $this->assertJsonResponse();
    $this->assertIdentifier();
  }
  public function testIndex()
  {
    $this->client->request('GET', '/buildings');
    $this->assertResponseCode(200);
    $this->assertJsonResponse();

    $this->client->request('GET', '/buildings?page=1');
    $this->assertResponseCode(200);
    $this->assertJsonResponse();

    $this->client->request('GET', '/buildings?page=1&size=1');
    $this->assertResponseCode(200);
    $this->assertJsonResponse();

    $this->client->request('GET', '/buildings?size=1');
    $this->assertResponseCode(200);
    $this->assertJsonResponse();
  }
  public function testUpdate()
  {
    $this->client->request(
      'PUT',
      '/buildings/' . self::$identifier,
      array(), // Parameters
      array(), // Files
      array('CONTENT_TYPE' => 'application/json'), // Server
      <<<JSON
        {
          "name": "Lenorae",
          "caste": "Archer"
        }
      JSON
    );
    $this->assertResponseCode(204);
  }
  public function testDelete()
  {
    $this->client->request('DELETE', '/buildings/' . self::$identifier);
    $this->assertResponseCode(204);
  }

  ###
  # failing tests
  public function testDeleteInexistingIdentifier()
  {
    $this->client->request('DELETE', '/buildings/8f74f20597c5cf99dd42cd31331b7e6e2aeerror');
    $this->assertResponseCode(404);
  }
  // more failing test
  public function testBadIdentifier()
  {
    $this->client->request('GET', '/buildings/badIdentifier');
    $this->assertResponseCode(404);
  }
  public function testInexistingIdentifier()
  {
    $this->client->request('GET', '/buildings/8f74f20597c5cf99dd42cd31331b7e6e2aeerror');
    $this->assertResponseCode(404);
  }

  // private fn
  private function assertJsonResponse(): void
  {
    $response = $this->client->getResponse();
    $this->content = json_decode($response->getContent(), true, 50);
    $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
  }
  private function defineIdentifier(): void
  {
    $this->content = json_decode($this->client->getResponse()->getContent(), true);
    self::$identifier = $this->content['identifier'];
  }
  private function assertIdentifier(): void
  {
    $this->assertEquals(self::$identifier, $this->content['identifier']);
  }
  public function assertResponseCode(int $code): void
  {
    $this->assertSame($code, $this->client->getResponse()->getStatusCode());
  }
}
