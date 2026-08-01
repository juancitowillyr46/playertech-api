<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Http\Academy;

use App\Shared\Domain\Document\DocumentType;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/academy/document-types')]
final readonly class DocumentTypeController
{
    public function __construct(private TenantContext $tenantContext)
    {
    }

    #[Route('/options', name: 'api_v1_academy_document_types_options', methods: ['GET'])]
    public function options(): JsonResponse
    {
        $this->tenantContext->requireAcademyId();

        return new JsonResponse([
            'data' => DocumentType::options(),
            'meta' => new \stdClass(),
        ]);
    }
}
