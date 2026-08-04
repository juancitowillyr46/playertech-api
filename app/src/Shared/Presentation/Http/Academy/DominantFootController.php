<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Http\Academy;

use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Shared\Domain\DominantFoot\DominantFoot;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/academy/dominant-feet')]
final readonly class DominantFootController
{
    public function __construct(private TenantContext $tenantContext)
    {
    }

    #[Route('/options', name: 'api_v1_academy_dominant_feet_options', methods: ['GET'])]
    public function options(): JsonResponse
    {
        $this->tenantContext->requireAcademyId();

        return new JsonResponse([
            'data' => DominantFoot::options(),
            'meta' => new \stdClass(),
        ]);
    }
}
