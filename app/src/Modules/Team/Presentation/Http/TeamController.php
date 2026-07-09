<?php

declare(strict_types=1);

namespace App\Modules\Team\Presentation\Http;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Team\Application\Command\ActivateTeamCommand;
use App\Modules\Team\Application\Command\CreateTeamCommand;
use App\Modules\Team\Application\Command\InactivateTeamCommand;
use App\Modules\Team\Application\Command\UpdateTeamCommand;
use App\Modules\Team\Application\Handler\ActivateTeamHandler;
use App\Modules\Team\Application\Handler\CreateTeamHandler;
use App\Modules\Team\Application\Handler\InactivateTeamHandler;
use App\Modules\Team\Application\Handler\ListTeamsHandler;
use App\Modules\Team\Application\Handler\ShowTeamHandler;
use App\Modules\Team\Application\Handler\UpdateTeamHandler;
use App\Modules\Team\Application\Query\ListTeamsQuery;
use App\Modules\Team\Application\Query\ShowTeamQuery;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Presentation\Http\Request\CreateTeamRequest;
use App\Modules\Team\Presentation\Http\Request\UpdateTeamRequest;
use App\Shared\Presentation\Http\AbstractPaginatedApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/academy/teams')]
final class TeamController extends AbstractPaginatedApiController
{
    public function __construct(
        private readonly Security $security,
        private readonly ValidatorInterface $validator,
        private readonly CreateTeamHandler $createTeamHandler,
        private readonly UpdateTeamHandler $updateTeamHandler,
        private readonly ListTeamsHandler $listTeamsHandler,
        private readonly ShowTeamHandler $showTeamHandler,
        private readonly InactivateTeamHandler $inactivateTeamHandler,
        private readonly ActivateTeamHandler $activateTeamHandler,
        private readonly TenantContext $tenantContext,
    ) {
    }

    #[Route('', name: 'api_v1_teams_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $input = CreateTeamRequest::fromArray($request->toArray());

        $this->assertValid($this->validator, $input);

        $view = ($this->createTeamHandler)(
            new CreateTeamCommand(
                $this->requireActorId(),
                $this->tenantContext->requireAcademyId(),
                $input->toInput(),
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ], 201);
    }

    #[Route('', name: 'api_v1_teams_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $teams = ($this->listTeamsHandler)(
            new ListTeamsQuery(
                new AcademyId($this->tenantContext->requireAcademyId()),
                $this->paginationQueryFromRequest($request, 'audit_trail.created_at.value'),
            )
        );

        return new JsonResponse([
            'data' => array_map(static fn ($item) => $item->toArray(), $teams->items),
            'meta' => $teams->meta->toArray(),
        ]);
    }

    #[Route('/{teamId}', name: 'api_v1_teams_show', methods: ['GET'])]
    public function show(string $teamId): JsonResponse
    {
        $team = ($this->showTeamHandler)(
            new ShowTeamQuery(
                new AcademyId($this->tenantContext->requireAcademyId()),
                new TeamId($teamId),
            )
        );

        return new JsonResponse([
            'data' => $team->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{teamId}', name: 'api_v1_teams_update', methods: ['PUT'])]
    public function update(string $teamId, Request $request): JsonResponse
    {
        $input = UpdateTeamRequest::fromArray($request->toArray());

        $this->assertValid($this->validator, $input);

        $view = ($this->updateTeamHandler)(
            new UpdateTeamCommand(
                $this->requireActorId(),
                $this->tenantContext->requireAcademyId(),
                $teamId,
                $input->toInput(),
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{teamId}/inactivate', name: 'api_v1_teams_inactivate', methods: ['PATCH'])]
    public function inactivate(string $teamId): Response
    {
        ($this->inactivateTeamHandler)(
            new InactivateTeamCommand(
                $this->requireActorId(),
                $this->tenantContext->requireAcademyId(),
                $teamId,
            )
        );

        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    #[Route('/{teamId}/activate', name: 'api_v1_teams_activate', methods: ['PATCH'])]
    public function activate(string $teamId): Response
    {
        ($this->activateTeamHandler)(
            new ActivateTeamCommand(
                $this->requireActorId(),
                $this->tenantContext->requireAcademyId(),
                $teamId,
            )
        );

        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    private function requireActorId(): string
    {
        return $this->requireAuthenticatedUserId($this->security);
    }
}
