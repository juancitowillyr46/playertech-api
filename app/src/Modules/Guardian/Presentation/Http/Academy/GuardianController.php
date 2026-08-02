<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Presentation\Http\Academy;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Application\Command\ActivateLegalGuardianCommand;
use App\Modules\Guardian\Application\Command\CreateLegalGuardianCommand;
use App\Modules\Guardian\Application\Command\InactivateLegalGuardianCommand;
use App\Modules\Guardian\Application\Command\UpdateLegalGuardianCommand;
use App\Modules\Guardian\Application\Player\ListByGuardian\ListGuardianPlayersHandler;
use App\Modules\Guardian\Application\Player\ListByGuardian\ListGuardianPlayersQuery;
use App\Modules\Guardian\Application\Handler\ActivateLegalGuardianHandler;
use App\Modules\Guardian\Application\Handler\InactivateLegalGuardianHandler;
use App\Modules\Guardian\Application\Handler\ListLegalGuardiansHandler;
use App\Modules\Guardian\Application\Handler\CreateLegalGuardianHandler;
use App\Modules\Guardian\Application\Handler\ShowLegalGuardianHandler;
use App\Modules\Guardian\Application\Handler\UpdateLegalGuardianHandler;
use App\Modules\Guardian\Application\Query\ListLegalGuardiansQuery;
use App\Modules\Guardian\Application\Query\ShowLegalGuardianQuery;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Guardian\Presentation\Http\Request\CreateLegalGuardianRequest;
use App\Modules\Guardian\Presentation\Http\Request\UpdateLegalGuardianRequest;
use App\Shared\Presentation\Http\AbstractApiController;
use App\Shared\Presentation\Http\AbstractPaginatedApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        private readonly ListGuardianPlayersHandler $listGuardianPlayersHandler,
        private readonly ShowLegalGuardianHandler $showLegalGuardianHandler,
        private readonly UpdateLegalGuardianHandler $updateLegalGuardianHandler,
        private readonly InactivateLegalGuardianHandler $inactivateLegalGuardianHandler,
        private readonly ActivateLegalGuardianHandler $activateLegalGuardianHandler,
        private readonly TenantContext $tenantContext,
    ) {
    }

    #[Route('', name: 'api_v1_academy_guardians_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $items = ($this->listLegalGuardiansHandler)(
            new ListLegalGuardiansQuery(
                new AcademyId($this->tenantContext->requireAcademyId()),
                $this->paginationQueryFromRequest($request, 'auditTrail.createdAt.value'),
                $this->optionalQueryString($request, 'documentNumber'),
                $this->optionalQueryString($request, 'documentType'),
                $this->optionalQueryString($request, 'firstName'),
                $this->optionalQueryString($request, 'lastName'),
                $this->optionalQueryString($request, 'fullName'),
            )
        );

        return new JsonResponse([
            'data' => array_map(static fn ($item) => $item->toArray(), $items->items),
            'meta' => $items->meta->toArray(),
        ]);
    }

    private function optionalQueryString(Request $request, string $key): ?string
    {
        $value = trim((string) $request->query->get($key, ''));

        return '' === $value ? null : $value;
    }

    #[Route('/{guardianId}/players', name: 'api_v1_academy_guardians_players_list', methods: ['GET'])]
    public function listPlayers(string $guardianId, Request $request): JsonResponse
    {
        $items = ($this->listGuardianPlayersHandler)(
            new ListGuardianPlayersQuery(
                new AcademyId($this->tenantContext->requireAcademyId()),
                new \App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId($guardianId),
                $this->paginationQueryFromRequest($request, 'auditTrail.createdAt.value'),
            )
        );

        return new JsonResponse([
            'data' => array_map(static fn ($item) => $item->toArray(), $items),
            'meta' => new \stdClass(),
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

    #[Route('/{guardianId}', name: 'api_v1_academy_guardians_update', methods: ['PUT'])]
    public function update(string $guardianId, Request $request): JsonResponse
    {
        $input = UpdateLegalGuardianRequest::fromArray($request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->updateLegalGuardianHandler)(
            new UpdateLegalGuardianCommand(
                $this->requireAuthenticatedUserId($this->security),
                new AcademyId($this->tenantContext->requireAcademyId()),
                $guardianId,
                $input->toInput(),
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{guardianId}/inactivate', name: 'api_v1_academy_guardians_inactivate', methods: ['PATCH'])]
    public function inactivate(string $guardianId): Response
    {
        ($this->inactivateLegalGuardianHandler)(
            new InactivateLegalGuardianCommand(
                $this->requireAuthenticatedUserId($this->security),
                new AcademyId($this->tenantContext->requireAcademyId()),
                $guardianId,
            )
        );

        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    #[Route('/{guardianId}/activate', name: 'api_v1_academy_guardians_activate', methods: ['PATCH'])]
    public function activate(string $guardianId): Response
    {
        ($this->activateLegalGuardianHandler)(
            new ActivateLegalGuardianCommand(
                $this->requireAuthenticatedUserId($this->security),
                new AcademyId($this->tenantContext->requireAcademyId()),
                $guardianId,
            )
        );

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
