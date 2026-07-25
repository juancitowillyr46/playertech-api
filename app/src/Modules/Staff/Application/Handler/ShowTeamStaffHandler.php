<?php
declare(strict_types=1);
namespace App\Modules\Staff\Application\Handler;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Application\Query\ShowTeamStaffQuery;
use App\Modules\Staff\Application\Response\TeamStaffMemberResponse;
use App\Modules\Staff\Domain\Staff\StaffRepository;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignmentRepository;
use Doctrine\ORM\EntityManagerInterface;
final readonly class ShowTeamStaffHandler
{
    public function __construct(
        private StaffRepository $staffRepository,
        private TeamStaffAssignmentRepository $assignmentRepository,
        private EntityManagerInterface $entityManager,
    ) {}
    public function __invoke(ShowTeamStaffQuery $query): array
    {
        $assignments = $this->assignmentRepository->findAllByTeam($query->academyId, $query->teamId);
        return array_values(array_filter(array_map(function ($assignment) use ($query) {
            $staff = $this->staffRepository->findById($query->academyId, $assignment->staffId());
            if (null === $staff) {
                return null;
            }

            /** @var AccountUser|null $user */
            $user = $this->entityManager->getRepository(AccountUser::class)->find($staff->userId());

            return TeamStaffMemberResponse::fromEntities($assignment, $staff, $user instanceof AccountUser ? $user : null);
        }, $assignments)));
    }
}
