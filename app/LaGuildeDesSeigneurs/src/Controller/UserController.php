<?php

namespace App\Controller;

use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use OpenApi\Annotations as OB;

class UserController extends AbstractController
{
    public function __construct(private UserServiceInterface $userService)
    {
    }

    /**
     * @OB\RequestBody(
     *      required=true,
     *      @OB\JsonContent(
     *          example={
     *              "username": "email",
     *              "password": "password"
     *          },
     *      @OB\Schema (
     *          type="array",
     *          @OB\Property(property="status", required=true, description="Event Status", type="string"),
     *          @OB\Property(property="comment", required=false, description="Change Status Comment", type="string"),
     *      )
     *   )
     * )
     */
    #[OA\Tag(name: 'User')]
    #[OA\Response(
        response: 200,
        description: 'Returns a JWT',
        content: new OA\JsonContent(
            type: 'string',
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found'
    )]
    #[Route(
        '/signin',
        name: 'app_signin',
        methods: ['POST']
    )]
    public function signin(): JsonResponse
    {
        $user = $this->getUser();

        if (null !== $user) {
            return new JsonResponse([
                'token' => $this->userService->getToken($user),
            ]);
        }
        return new JsonResponse([
            'error' => 'User not found',
        ]);
    }
}
