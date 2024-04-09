<?php

namespace App\Controller;

use App\Entity\Character;
use App\Service\CharacterServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CharacterController extends AbstractController
{
  public function __construct(private CharacterServiceInterface $characterService)
  {
  }

  #[
    Route(
      '/characters/{identifier}',
      requirements: ['identifier' => '^([a-z0-9]{40})$'],
      name: 'app_character_display',
      methods: ['GET']
    )
  ]
  public function display(Character $character): JsonResponse
  {
    $this->denyAccessUnlessGranted('characterDisplay', $character);

    return new JsonResponse($character->toArray());
  }

  // src/Controller/CharacterController.php
  // CREATE
  #[Route('/characters', name: 'app_character_create', methods: ['POST'])]
  public function create(): JsonResponse
  {
    $character = $this->characterService->create();

    return new JsonResponse($character->toArray(), JsonResponse::HTTP_CREATED);
    $response = new JsonResponse($character->toArray(), JsonResponse::HTTP_CREATED);
    $url = $this->generateUrl(
      'app_character_display',
      ['identifier' => $character->getIdentifier()]
    );
    $response->headers->set('Location', $url);
    return $response;
  }
}
