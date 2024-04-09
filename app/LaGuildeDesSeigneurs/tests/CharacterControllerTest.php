<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTetsTest extends WebTestCase
{

  private $client;

  public function setUp(): void
  {
    $this->client = static::createClient();
  }

  public function testBadIdentifier()
  {
    $this->client->request('GET', '/characters/badIdentifier');
    $this->assertError404();
  }

  public function testInexistingIdentifier()
  {
    $this->client->request('GET', '/characters/8f74f20597c5cf99dd42cd31331b7e6e2aeerror');
    $this->assertError404();
  }

  public function testDisplay(): void
  {
    $this->client->request('GET', '/characters/d1205792756337b7bbdc86bb12f2aa01a78136c1');

    $this->assertJsonResponse();
  }

  public function testIndex()
  {
    $this->client->request('GET', '/characters');
    $this->assertJsonResponse();
  }

  public function testUpdate()
  {
    $this->client->request('PUT', '/characters/d1205792756337b7bbdc86bb12f2aa01a78136c1');
    $this->assertResponseCode204();
  }
  // Asserts that Response code is 204
  public function assertResponseCode204()
  {
    $response = $this->client->getResponse();
    $this->assertEquals(204, $response->getStatusCode());
  }

  // private fn
  public function assertJsonResponse(): void
  {
    $response = $this->client->getResponse();
    $this->assertResponseIsSuccessful();
    $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
  }

  // Asserts that Response returns 404
  public function assertError404()
  {
    $response = $this->client->getResponse();
    $this->assertEquals(404, $response->getStatusCode());
  }
}
