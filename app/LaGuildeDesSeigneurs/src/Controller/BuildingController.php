<?php

namespace App\Controller;

use App\Entity\Building;
use App\Service\BuildingServiceInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class BuildingController extends AbstractController
{
  public function __construct(private BuildingServiceInterface $buildingService)
  {
  }

  #[
    Route(
      '/buildings',
      name: 'app_building_index',
      methods: ['GET']
    )
  ]
  public function index(Request $request): JsonResponse
  {
    $this->denyAccessUnlessGranted('buildingIndex', null);
    $buildings = $this->buildingService->findAll($request->query);

    return JsonResponse::fromJsonString($this->buildingService->serializeJson($buildings));
  }

  #[
    Route(
      '/buildings',
      name: 'app_building_create',
      methods: ['POST']
    )
  ]
  public function create(Request $request): JsonResponse
  {
    $this->denyAccessUnlessGranted('buildingCreate', null);
    $building = $this->buildingService->create($request->getContent());

    $response = JsonResponse::fromJsonString($this->buildingService->serializeJson($building), JsonResponse::HTTP_CREATED);
    $url = $this->generateUrl(
      'app_building_display',
      ['identifier' => $building->getIdentifier()]
    );
    $response->headers->set('Location', $url);

    return $response;
  }

  #[
    Route(
      '/buildings/{identifier}',
      requirements: ['identifier' => '^([a-z0-9]{40})$'],
      name: 'app_building_display',
      methods: ['GET']
    )
  ]
  public function display(
    #[MapEntity(expr: 'repository.findOneByIdentifier(identifier)')]
    Building $building
  ): JsonResponse {
    $this->denyAccessUnlessGranted('buildingDisplay', $building);
    // Mais on l'utilise de manière statique
    // d'où l'utilisation des ::
    // et on appelle la méthode fromJsonString()
    return JsonResponse::fromJsonString($this->buildingService->serializeJson($building));
  }

  #[
    Route(
      '/buildings/{identifier}',
      requirements: ['identifier' => '^([a-z0-9]{40})$'],
      name: 'app_building_update',
      methods: ['PUT']
    )
  ]
  public function update(Building $building, Request $request): JsonResponse
  {
    $this->denyAccessUnlessGranted('buildingUpdate', $building);
    $this->buildingService->update($building, $request->getContent());

    return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }

  #[
    Route(
      '/buildings/{identifier}',
      requirements: ['identifier' => '^([a-z0-9]{40})$'],
      name: 'app_building_delete',
      methods: ['DELETE']
    )
  ]
  public function delete(Building $building): JsonResponse
  {
    $this->denyAccessUnlessGranted('buildingDelete', $building);
    $this->buildingService->delete($building);

    return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }
}
