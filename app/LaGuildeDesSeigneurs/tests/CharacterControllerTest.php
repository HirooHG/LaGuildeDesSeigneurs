<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTetsTest extends WebTestCase
{

  private $client;

  private $content;
  private static $identifier;

  public function setUp(): void
  {
    $this->client = static::createClient();
  }

  ###
  # passing tests
  public function testCreate()
  {
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
          "image": "/dames/maeglin.webp"
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

    $this->client->request('GET', '/characters/?page=1');
    $this->assertResponseCode(200);
    $this->assertJsonResponse();

    $this->client->request('GET', '/characters/?page=1&size=1');
    $this->assertResponseCode(200);
    $this->assertJsonResponse();

    $this->client->request('GET', '/characters/?size=1');
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
