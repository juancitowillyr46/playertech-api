<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Staff\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Staff\Application\Handler\ShowTeamStaffHandler;
use App\Modules\Staff\Application\Query\ShowTeamStaffQuery;
use App\Modules\Staff\Domain\Staff\Staff;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Modules\Staff\Domain\TeamStaffAssignment\StaffRole;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignment;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignmentId;
use App\Modules\Team\Domain\Team\TeamId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;
final class ShowTeamStaffHandlerTest extends TestCase
{
    public function testItListsTeamStaff(): void
    {
        $staffRepository = new InMemoryStaffRepository();
        $assignmentRepository = new InMemoryTeamStaffAssignmentRepository();
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $staff = Staff::create(StaffId::generate(), $academyId, '019eec93-9a11-7432-bd04-52306b2b3d8e', AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d00'));
        $staffRepository->save($staff);
        $assignment = TeamStaffAssignment::create(TeamStaffAssignmentId::generate(), $academyId, $staff->id(), new TeamId('019eec93-9a11-7432-bd04-52306b2b3d88'), new StaffRole(StaffRole::HEAD_COACH), AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d00'));
        $assignmentRepository->save($assignment);
        $handler = new ShowTeamStaffHandler($staffRepository, $assignmentRepository);
        $items = $handler(new ShowTeamStaffQuery($academyId, new TeamId('019eec93-9a11-7432-bd04-52306b2b3d88')));
        self::assertCount(1, $items);
        self::assertSame(StaffRole::HEAD_COACH, $items[0]->toArray()['role']);
    }
}
