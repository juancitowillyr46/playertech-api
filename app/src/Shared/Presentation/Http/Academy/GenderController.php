<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Http\Academy;

use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Shared\Domain\Gender\Gender;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/academy/genders')]
final readonly class GenderController
{
    public function __construct(private TenantContext $tenantContext)
    {
    }

    #[Route('/options', name: 'api_v1_academy_genders_options', methods: ['GET'])]
    public function options(): JsonResponse
    {
        $this->tenantContext->requireAcademyId();

        return new JsonResponse([
            'data' => Gender::options(),
            'meta' => new \stdClass(),
        ]);
    }
}
