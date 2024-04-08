<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTetsTest extends WebTestCase
{
  public function testDisplay(): void
  {
    $client = static::createClient();
    $client->request('GET', '/characters/');
    $response = $client->getResponse();

    $this->assertJsonResponse($client->getResponse());
  }

  public function assertJsonResponse($response): void
  {
    $this->assertResponseIsSuccessful();
    $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
  }
}
