<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTetsTest extends WebTestCase
{
  public function testDisplay(): void
  {
    $client = static::createClient();
    $client->request('GET', '/characters/d1205792756337b7bbdc86bb12f2aa01a78136c1');

    $this->assertJsonResponse($client->getResponse());
  }

  public function assertJsonResponse($response): void
  {
    $this->assertResponseIsSuccessful();
    $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
  }
}
