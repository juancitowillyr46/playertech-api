<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Http\Academy;

use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Shared\Domain\Relationship\Relationship;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/academy/relationships')]
final readonly class RelationshipController
{
    public function __construct(private TenantContext $tenantContext)
    {
    }

    #[Route('/options', name: 'api_v1_academy_relationships_options', methods: ['GET'])]
    public function options(): JsonResponse
    {
        $this->tenantContext->requireAcademyId();

        return new JsonResponse([
            'data' => Relationship::options(),
            'meta' => new \stdClass(),
        ]);
    }
}
