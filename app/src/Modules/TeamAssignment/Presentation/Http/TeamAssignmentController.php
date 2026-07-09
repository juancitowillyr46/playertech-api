<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Presentation\Http;

use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\TeamAssignment\Application\Command\AssignPlayerToTeamCommand;
use App\Modules\TeamAssignment\Application\Command\FinalizeTeamAssignmentCommand;
use App\Modules\TeamAssignment\Application\Command\MarkTeamAssignmentPrimaryCommand;
use App\Modules\TeamAssignment\Application\Handler\AssignPlayerToTeamHandler;
use App\Modules\TeamAssignment\Application\Handler\FinalizeTeamAssignmentHandler;
use App\Modules\TeamAssignment\Application\Handler\MarkTeamAssignmentPrimaryHandler;
use App\Modules\TeamAssignment\Application\Handler\ShowPlayerTeamAssignmentsHandler;
use App\Modules\TeamAssignment\Application\Query\ShowPlayerTeamAssignmentsQuery;
use App\Modules\TeamAssignment\Presentation\Http\Request\AssignPlayerToTeamRequest;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/academy/team-assignments')]
final class TeamAssignmentController extends AbstractApiController
{
    public function __construct(
        private readonly Security $security,
        private readonly ValidatorInterface $validator,
        private readonly AssignPlayerToTeamHandler $assignPlayerToTeamHandler,
        private readonly MarkTeamAssignmentPrimaryHandler $markTeamAssignmentPrimaryHandler,
        private readonly FinalizeTeamAssignmentHandler $finalizeTeamAssignmentHandler,
        private readonly ShowPlayerTeamAssignmentsHandler $showPlayerTeamAssignmentsHandler,
        private readonly TenantContext $tenantContext
    ) {
    }

    #[Route('', methods: ['POST'])]
    public function assign(Request $request): JsonResponse
    {
        $input = new AssignPlayerToTeamRequest($request->toArray()['player_id'] ?? null, $request->toArray()['team_id'] ?? null, $request->toArray()['start_date'] ?? null);
        $this->assertValid($this->validator, $input);
        $view = ($this->assignPlayerToTeamHandler)(new AssignPlayerToTeamCommand(
            $this->requireAuthenticatedUserId($this->security),
            $this->tenantContext->requireAcademyId(),
            $input->playerId ?? '',
            $input->teamId ?? '',
            $input->startDate ?? (new \DateTimeImmutable())->format('Y-m-d')
        ));

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/{assignmentId}/primary', methods: ['PATCH'])]
    public function markPrimary(string $assignmentId): JsonResponse
    {
        $view = ($this->markTeamAssignmentPrimaryHandler)(new MarkTeamAssignmentPrimaryCommand(
            $this->requireAuthenticatedUserId($this->security),
            $this->tenantContext->requireAcademyId(),
            $assignmentId
        ));

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{assignmentId}/finalize', methods: ['PATCH'])]
    public function finalize(string $assignmentId): JsonResponse
    {
        $view = ($this->finalizeTeamAssignmentHandler)(new FinalizeTeamAssignmentCommand(
            $this->requireAuthenticatedUserId($this->security),
            $this->tenantContext->requireAcademyId(),
            $assignmentId
        ));

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/players/{playerId}', methods: ['GET'])]
    public function listByPlayer(string $playerId): JsonResponse
    {
        $views = ($this->showPlayerTeamAssignmentsHandler)(new ShowPlayerTeamAssignmentsQuery(
            $this->tenantContext->requireAcademyId(),
            $playerId
        ));

        return new JsonResponse([
            'data' => array_map(static fn ($view) => $view->toArray(), $views),
            'meta' => new \stdClass(),
        ]);
    }
}
