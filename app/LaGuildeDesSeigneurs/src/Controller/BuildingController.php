<?php

namespace App\Controller;

use App\Entity\Building;
use App\Service\BuildingServiceInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class BuildingController extends AbstractController
{
  public function __construct(private BuildingServiceInterface $buildingService)
  {
  }
  #[OA\Response(
    response: 200,
    description: 'Returns an array of Buildings',
    content: new OA\JsonContent(
      type: 'array',
      items: new OA\Items(ref: new Model(type: Building::class))
    )
  )]
  #[OA\Response(
    response: 403,
    description: 'Access denied'
  )]
  #[OA\Tag(name: 'Building')]
  #[Cache(public: true, maxage: 3600, mustRevalidate: true)]
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
    $buildings = $this->buildingService->serializeJson($this->buildingService->findAllPaginated($request->query));

    return JsonResponse::fromJsonString($buildings);
  }

  #[OA\RequestBody(
    request: "Building",
    description: "Data for the Building",
    required: true,
    content: new OA\JsonContent(
      type: Building::class,
      example: [
        "name" => "Château Silken",
        "caste" => "Archer",
        "image" => "/buildings/chateau-silken.webp",
        "strength" => 1200,
        "rate" => 4
      ]
    )
  )]
  #[OA\Response(
    response: 201,
    description: 'Returns the Building',
    content: new Model(type: Building::class)
  )]
  #[OA\Response(
    response: 403,
    description: 'Access denied'
  )]
  #[OA\Tag(name: 'Building')]
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

  #[OA\Parameter(
    name: 'identifier',
    description: 'Identifier for the Building',
    in: 'path',
    required: true,
    schema: new OA\Schema(type: 'string')
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns the Building',
    content: new OA\JsonContent(ref: new Model(type: Building::class))
  )]
  #[OA\Response(
    response: 403,
    description: 'Access denied'
  )]
  #[OA\Response(
    response: 404,
    description: 'Not found'
  )]
  #[OA\Tag(name: 'Building')]
  #[Cache(public: true, maxage: 3600, mustRevalidate: true)]
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
    return JsonResponse::fromJsonString($this->buildingService->serializeJson($building));
  }

  #[OA\Parameter(
    name: 'identifier',
    description: 'Identifier for the Building',
    in: 'path',
    required: true,
    schema: new OA\Schema(type: 'string')
  )]
  #[OA\RequestBody(
    request: "Building",
    description: "Data for the Building",
    required: true,
    content: new OA\JsonContent(
      type: Building::class,
      example: [
        "name" => "Citadelle",
        "caste" => "Mage",
      ]
    )
  )]
  #[OA\Response(
    response: 204,
    description: 'No content'
  )]
  #[OA\Response(
    response: 403,
    description: 'Access denied'
  )]
  #[OA\Response(
    response: 404,
    description: 'Not found'
  )]
  #[OA\Tag(name: 'Building')]
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

  #[OA\Parameter(
    name: 'identifier',
    description: 'Identifier for the Building',
    in: 'path',
    required: true,
    schema: new OA\Schema(type: 'string')
  )]
  #[OA\Response(
    response: 204,
    description: 'No content'
  )]
  #[OA\Response(
    response: 403,
    description: 'Access denied'
  )]
  #[OA\Response(
    response: 404,
    description: 'Not found'
  )]
  #[OA\Tag(name: 'Building')]
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

  // IMAGES
  #[OA\Parameter(
    name: 'number',
    description: 'Number of images',
    in: 'path',
    required: false,
    schema: new OA\Schema(type: 'integer')
  )]
  #[OA\Response(
    response: 200,
    description: 'Returns links for images'
  )]
  #[OA\Response(
    response: 403,
    description: 'Access denied'
  )]
  #[OA\Tag(name: 'Building')]
  #[Route(
    '/buildings/images/{number}',
    name: 'app_building_images',
    requirements: ['number' => '^([0-9]{1,2})$'],
    methods: ['GET']
  )]
  public function images(int $number = 1): JsonResponse
  {
    $this->denyAccessUnlessGranted('buildingIndex', null);
    $images = $this->buildingService->getImages($number);
    return new JsonResponse($images);
  }
}
