<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Staff\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Staff\Application\Command\AssignStaffToTeamCommand;
use App\Modules\Staff\Application\Handler\AssignStaffToTeamHandler;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Modules\Staff\Domain\TeamStaffAssignment\StaffRole;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;
final class AssignStaffToTeamHandlerTest extends TestCase
{
    public function testItAssignsStaffToTeam(): void
    {
        $staffRepository = new InMemoryStaffRepository();
        $teamRepository = $this->createMock(\App\Modules\Team\Domain\Team\TeamRepository::class);
        $assignmentRepository = new InMemoryTeamStaffAssignmentRepository();
        $staff = \App\Modules\Staff\Domain\Staff\Staff::create(StaffId::generate(), new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f'), '019eec93-9a11-7432-bd04-52306b2b3d8e', AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d00'));
        $staffRepository->save($staff);
        $team = Team::create(\App\Modules\Team\Domain\Team\TeamId::generate(), new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f'), new \App\Modules\Category\Domain\Category\CategoryId('019eec93-9a11-7432-bd04-52306b2b3d88'), new Name('Sub 12 A'), AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d00'));
        $teamRepository->method('findById')->willReturn($team);
        $handler = new AssignStaffToTeamHandler($staffRepository, $teamRepository, $assignmentRepository);
        $response = $handler(new AssignStaffToTeamCommand('019eec93-9a11-7432-bd04-52306b2b3d00', '019eec93-9a11-7432-bd04-52306b2b3d8f', $staff->id()->value(), $team->id()->value(), new StaffRole(StaffRole::HEAD_COACH)));
        self::assertSame(StaffRole::HEAD_COACH, $response->toArray()['role']);
        self::assertCount(1, $assignmentRepository->items);
    }
}
