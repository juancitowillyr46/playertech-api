<?php

namespace App\Modules\Identity\Presentation\Http\Auth;

use App\Modules\Identity\Domain\User\AccountUser;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MeController
{
    #[Route('/auth/me', name: 'api_v1_auth_me', methods: ['GET'])]
    public function __invoke(Security $security): JsonResponse
    {
        $user = $security->getUser();

        if (!$user instanceof AccountUser) {
            return new JsonResponse([
                'type' => 'https://api.playertech/errors/authentication',
                'title' => 'Authentication Error',
                'status' => Response::HTTP_UNAUTHORIZED,
                'detail' => 'No autenticado.',
                'instance' => '/api/v1/auth/me',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'data' => [
                'id' => $user->getId(),
                'email' => $user->getUserIdentifier(),
                'academy_id' => $user->getAcademyId(),
                'role' => $user->getRole(),
                'status' => $user->getStatus(),
            ],
            'meta' => new \stdClass(),
        ]);
    }
}
