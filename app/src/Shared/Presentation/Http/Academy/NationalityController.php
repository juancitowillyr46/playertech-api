<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Http\Academy;

use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Shared\Domain\Nationality\Nationality;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/academy/nationalities')]
final readonly class NationalityController
{
    public function __construct(private TenantContext $tenantContext)
    {
    }

    #[Route('/options', name: 'api_v1_academy_nationalities_options', methods: ['GET'])]
    public function options(): JsonResponse
    {
        $this->tenantContext->requireAcademyId();

        return new JsonResponse([
            'data' => Nationality::options(),
            'meta' => new \stdClass(),
        ]);
    }
}
