<?php

namespace App\Controller;

use App\Entity\Character;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CharacterController extends AbstractController
{
  #[Route('/characters/', name: 'app_character_display')]
  public function display(): JsonResponse
  {
    $charcter = new Character();

    return new JsonResponse($charcter->toArray());
  }
}
