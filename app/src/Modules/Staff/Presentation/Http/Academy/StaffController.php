<?php
declare(strict_types=1);
namespace App\Modules\Staff\Presentation\Http\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Modules\Staff\Application\Command\AssignStaffToTeamCommand;
use App\Modules\Staff\Application\Command\ChangeStaffRoleCommand;
use App\Modules\Staff\Application\Command\RegisterStaffMemberCommand;
use App\Modules\Staff\Application\Command\RemoveStaffFromTeamCommand;
use App\Modules\Staff\Application\Handler\AssignStaffToTeamHandler;
use App\Modules\Staff\Application\Handler\ChangeStaffRoleHandler;
use App\Modules\Staff\Application\Handler\RegisterStaffMemberHandler;
use App\Modules\Staff\Application\Handler\RemoveStaffFromTeamHandler;
use App\Modules\Staff\Application\Handler\ShowTeamStaffHandler;
use App\Modules\Staff\Application\Query\ShowTeamStaffQuery;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Modules\Staff\Domain\TeamStaffAssignment\StaffRole;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignmentId;
use App\Modules\Staff\Presentation\Http\Request\AssignStaffToTeamRequest;
use App\Modules\Staff\Presentation\Http\Request\ChangeStaffRoleRequest;
use App\Modules\Staff\Presentation\Http\Request\RegisterStaffMemberRequest;
use App\Modules\Team\Domain\Team\TeamId;
use App\Shared\Presentation\Http\AbstractApiController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/academy/staff')]
final class StaffController extends AbstractApiController
{
    public function __construct(
        private readonly Security $security,
        private readonly ValidatorInterface $validator,
        private readonly RegisterStaffMemberHandler $registerStaffMemberHandler,
        private readonly AssignStaffToTeamHandler $assignStaffToTeamHandler,
        private readonly ChangeStaffRoleHandler $changeStaffRoleHandler,
        private readonly RemoveStaffFromTeamHandler $removeStaffFromTeamHandler,
        private readonly ShowTeamStaffHandler $showTeamStaffHandler,
        private readonly TenantContext $tenantContext,
    ) {
    }

    #[Route('', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $input = new RegisterStaffMemberRequest(...$request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->registerStaffMemberHandler)(
            new RegisterStaffMemberCommand(
                $this->requireAuthenticatedUserId($this->security),
                $this->tenantContext->requireAcademyId(),
                $input->userId,
            )
        );

        return new JsonResponse(['data' => $view->toArray(), 'meta' => new \stdClass()], 201);
    }

    #[Route('/assignments', methods: ['POST'])]
    public function assign(Request $request): JsonResponse
    {
        $input = new AssignStaffToTeamRequest(...$request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->assignStaffToTeamHandler)(
            new AssignStaffToTeamCommand(
                $this->requireAuthenticatedUserId($this->security),
                $this->tenantContext->requireAcademyId(),
                $input->staffId,
                $input->teamId,
                new StaffRole($input->role),
            )
        );

        return new JsonResponse(['data' => $view->toArray(), 'meta' => new \stdClass()], 201);
    }

    #[Route('/assignments/{assignmentId}/role', methods: ['PATCH'])]
    public function changeRole(Request $request, string $assignmentId): JsonResponse
    {
        $input = new ChangeStaffRoleRequest(...$request->toArray());
        $this->assertValid($this->validator, $input);

        $view = ($this->changeStaffRoleHandler)(
            new ChangeStaffRoleCommand(
                $this->requireAuthenticatedUserId($this->security),
                $this->tenantContext->requireAcademyId(),
                $assignmentId,
                new StaffRole($input->role),
            )
        );

        return new JsonResponse(['data' => $view->toArray(), 'meta' => new \stdClass()]);
    }

    #[Route('/assignments/{assignmentId}/remove', methods: ['PATCH'])]
    public function remove(string $assignmentId): Response
    {
        ($this->removeStaffFromTeamHandler)(
            new RemoveStaffFromTeamCommand($this->requireAuthenticatedUserId($this->security), $this->tenantContext->requireAcademyId(), $assignmentId)
        );
        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    #[Route('/teams/{teamId}', methods: ['GET'])]
    public function teamStaff(string $teamId): JsonResponse
    {
        $items = ($this->showTeamStaffHandler)(
            new ShowTeamStaffQuery(new AcademyId($this->tenantContext->requireAcademyId()), new TeamId($teamId))
        );
        return new JsonResponse(['data' => array_map(static fn ($item) => $item->toArray(), $items), 'meta' => new \stdClass()]);
    }
}
