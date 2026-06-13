<?php

declare(strict_types=1);

namespace App\Modules\Academy\Presentation\Http;

use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AcademyMeController
{
    #[Route('/academy/me', name: 'api_v1_academy_me', methods: ['GET'])]
    public function __invoke(TenantContext $tenantContext): JsonResponse
    {
        $academyId = $tenantContext->requireAcademyId();

        return new JsonResponse([
            'data' => [
                'mode' => $tenantContext->getMode(),
                'user_id' => $tenantContext->getUserId(),
                'academy_id' => $academyId,
                'role' => $tenantContext->getRole(),
                'roles' => $tenantContext->getRoles(),
            ],
            'meta' => new \stdClass(),
        ], Response::HTTP_OK);
    }
}
