<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Presentation\Http\Academy;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Application\Command\CreateLegalGuardianCommand;
use App\Modules\Guardian\Application\Handler\ListLegalGuardiansHandler;
use App\Modules\Guardian\Application\Handler\CreateLegalGuardianHandler;
use App\Modules\Guardian\Application\Handler\ShowLegalGuardianHandler;
use App\Modules\Guardian\Application\Query\ListLegalGuardiansQuery;
use App\Modules\Guardian\Application\Query\ShowLegalGuardianQuery;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Guardian\Presentation\Http\Request\CreateLegalGuardianRequest;
use App\Shared\Presentation\Http\AbstractApiController;
use App\Shared\Presentation\Http\AbstractPaginatedApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/academy/guardians')]
final class GuardianController extends AbstractPaginatedApiController
{
    public function __construct(
        private readonly Security $security,
        private readonly ValidatorInterface $validator,
        private readonly CreateLegalGuardianHandler $createLegalGuardianHandler,
        private readonly ListLegalGuardiansHandler $listLegalGuardiansHandler,
        private readonly ShowLegalGuardianHandler $showLegalGuardianHandler,
        private readonly TenantContext $tenantContext,
    ) {
    }

    #[Route('', name: 'api_v1_academy_guardians_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $items = ($this->listLegalGuardiansHandler)(
            new ListLegalGuardiansQuery(
                new AcademyId($this->tenantContext->requireAcademyId()),
                $this->paginationQueryFromRequest($request, 'auditTrail.createdAt.value')
            )
        );

        return new JsonResponse([
            'data' => array_map(static fn ($item) => $item->toArray(), $items->items),
            'meta' => $items->meta->toArray(),
        ]);
    }

    #[Route('/{guardianId}', name: 'api_v1_academy_guardians_show', methods: ['GET'])]
    public function show(string $guardianId): JsonResponse
    {
        $view = ($this->showLegalGuardianHandler)(
            new ShowLegalGuardianQuery(
                new AcademyId($this->tenantContext->requireAcademyId()),
                new \App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId($guardianId)
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('', name: 'api_v1_academy_guardians_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $input = CreateLegalGuardianRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->createLegalGuardianHandler)(
            new CreateLegalGuardianCommand(
                $this->requireAuthenticatedUserId($this->security),
                new AcademyId($this->tenantContext->requireAcademyId()),
                $input->toInput(),
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ], 201);
    }
}
