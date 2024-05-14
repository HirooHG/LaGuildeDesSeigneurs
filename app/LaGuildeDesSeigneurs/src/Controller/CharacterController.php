<?php

namespace App\Controller;

use App\Entity\Character;
use App\Service\CharacterServiceInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
  public function display(
    #[MapEntity(expr: 'repository.findOneByIdentifier(identifier)')]
    Character $character
  ): JsonResponse {
    $this->denyAccessUnlessGranted('characterDisplay', $character);

    return JsonResponse::fromJsonString($this->characterService->serializeJson($character));
  }

  #[
    Route(
      '/characters/{identifier}',
      requirements: ['identifier' => '^([a-z0-9]{40})$'],
      name: 'app_character_update',
      methods: ['PUT']
    )
  ]
  public function update(Character $character, Request $request): JsonResponse
  {
    $this->denyAccessUnlessGranted('characterUpdate', $character);
    $this->characterService->update($character, $request->getContent());
    return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }

  // delete route
  #[
    Route(
      '/characters/{identifier}',
      requirements: ['identifier' => '^([a-z0-9]{40})$'],
      name: 'app_character_delete',
      methods: ['DELETE']
    )
  ]
  public function delete(Character $character): JsonResponse
  {
    $this->denyAccessUnlessGranted('characterDelete', $character);
    $this->characterService->delete($character);

    return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }

  #[
    Route(
      '/characters',
      name: 'app_character_index',
      methods: ['GET']
    )
  ]
  public function index(): JsonResponse
  {
    $this->denyAccessUnlessGranted('characterIndex', null);
    return JsonResponse::fromJsonString($this->characterService->findAllJson());
  }

  // src/Controller/CharacterController.php
  // CREATE
  #[Route('/characters', name: 'app_character_create', methods: ['POST'])]
  public function create(Request $request): JsonResponse
  {
    $character = $this->characterService->create($request->getContent());

    $response = JsonResponse::fromJsonString($this->characterService->serializeJson($character), JsonResponse::HTTP_CREATED);
    $url = $this->generateUrl(
      'app_character_display',
      ['identifier' => $character->getIdentifier()]
    );
    $response->headers->set('Location', $url);

    return $response;
  }
}
