<?php

declare(strict_types=1);

namespace App\Modules\Membership\Presentation\Http\Academy;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Membership\Application\Command\CreateMembershipCommand;
use App\Modules\Membership\Application\Command\SuspendMembershipCommand;
use App\Modules\Membership\Application\Command\WithdrawMembershipCommand;
use App\Modules\Membership\Application\Handler\CreateMembershipHandler;
use App\Modules\Membership\Application\Handler\ShowActiveMembershipHandler;
use App\Modules\Membership\Application\Handler\ShowMembershipHistoryHandler;
use App\Modules\Membership\Application\Handler\SuspendMembershipHandler;
use App\Modules\Membership\Application\Handler\WithdrawMembershipHandler;
use App\Modules\Membership\Application\Query\ShowActiveMembershipQuery;
use App\Modules\Membership\Application\Query\ShowMembershipHistoryQuery;
use App\Modules\Membership\Presentation\Http\Request\CreateMembershipRequest;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/academy/memberships')]
final class MembershipController extends AbstractApiController
{
    public function __construct(
        private readonly Security $security,
        private readonly ValidatorInterface $validator,
        private readonly CreateMembershipHandler $createMembershipHandler,
        private readonly ShowActiveMembershipHandler $showActiveMembershipHandler,
        private readonly ShowMembershipHistoryHandler $showMembershipHistoryHandler,
        private readonly SuspendMembershipHandler $suspendMembershipHandler,
        private readonly WithdrawMembershipHandler $withdrawMembershipHandler,
        private readonly TenantContext $tenantContext,
    ) {
    }

    #[Route('', name: 'api_v1_academy_memberships_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $input = new CreateMembershipRequest(
            $request->toArray()['player_id'] ?? null,
            $request->toArray()['primary_guardian_id'] ?? null,
        );
        $this->assertValid($this->validator, $input);

        $view = ($this->createMembershipHandler)(
            new CreateMembershipCommand(
                $this->requireAuthenticatedUserId($this->security),
                $this->tenantContext->requireAcademyId(),
                $input->playerId ?? '',
                $input->primaryGuardianId ?? '',
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ], 201);
    }

    #[Route('/{playerId}/active', name: 'api_v1_academy_memberships_show_active', methods: ['GET'])]
    public function showActive(string $playerId): JsonResponse
    {
        $view = ($this->showActiveMembershipHandler)(
            new ShowActiveMembershipQuery(
                new AcademyId($this->tenantContext->requireAcademyId()),
                new PlayerId($playerId),
            )
        );

        return new JsonResponse([
            'data' => $view->toArray(),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{playerId}/history', name: 'api_v1_academy_memberships_history', methods: ['GET'])]
    public function history(string $playerId): JsonResponse
    {
        $items = ($this->showMembershipHistoryHandler)(
            new ShowMembershipHistoryQuery(
                new AcademyId($this->tenantContext->requireAcademyId()),
                new PlayerId($playerId),
            )
        );

        return new JsonResponse([
            'data' => array_map(
                static fn ($item) => $item->toArray(),
                $items
            ),
            'meta' => new \stdClass(),
        ]);
    }

    #[Route('/{playerId}/suspend', name: 'api_v1_academy_memberships_suspend', methods: ['PATCH'])]
    public function suspend(string $playerId): JsonResponse
    {
        ($this->suspendMembershipHandler)(
            new SuspendMembershipCommand(
                $this->requireAuthenticatedUserId($this->security),
                $this->tenantContext->requireAcademyId(),
                $playerId,
            )
        );

        return new JsonResponse(null, 204);
    }

    #[Route('/{playerId}/withdraw', name: 'api_v1_academy_memberships_withdraw', methods: ['PATCH'])]
    public function withdraw(string $playerId): JsonResponse
    {
        ($this->withdrawMembershipHandler)(
            new WithdrawMembershipCommand(
                $this->requireAuthenticatedUserId($this->security),
                $this->tenantContext->requireAcademyId(),
                $playerId,
            )
        );

        return new JsonResponse(null, 204);
    }
}
