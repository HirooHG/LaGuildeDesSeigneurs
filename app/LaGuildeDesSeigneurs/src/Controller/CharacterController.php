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

  #[Route('/characters', name: 'app_character_display', methods: ['GET'])]
  public function display(): JsonResponse
  {
    $charcter = new Character();

    return new JsonResponse($charcter->toArray());
  }

  // src/Controller/CharacterController.php
  // CREATE
  #[Route('/characters', name: 'app_character_create', methods: ['POST'])]
  public function create(): JsonResponse
  {
    $character = $this->characterService->create();
    // dd($character);

    return new JsonResponse($character->toArray(), JsonResponse::HTTP_CREATED);
  }
}
