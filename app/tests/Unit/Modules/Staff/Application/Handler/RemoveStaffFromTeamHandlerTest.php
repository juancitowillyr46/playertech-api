<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Staff\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Staff\Application\Command\RemoveStaffFromTeamCommand;
use App\Modules\Staff\Application\Handler\RemoveStaffFromTeamHandler;
use App\Modules\Staff\Domain\TeamStaffAssignment\StaffRole;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignment;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignmentId;
use App\Modules\Team\Domain\Team\TeamId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;
final class RemoveStaffFromTeamHandlerTest extends TestCase
{
    public function testItRemovesStaffAssignment(): void
    {
        $repository = new InMemoryTeamStaffAssignmentRepository();
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $assignment = TeamStaffAssignment::create(TeamStaffAssignmentId::generate(), $academyId, \App\Modules\Staff\Domain\Staff\StaffId::generate(), new TeamId('019eec93-9a11-7432-bd04-52306b2b3d88'), new StaffRole(StaffRole::HEAD_COACH), AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d00'));
        $repository->save($assignment);
        $handler = new RemoveStaffFromTeamHandler($repository);
        $handler(new RemoveStaffFromTeamCommand('019eec93-9a11-7432-bd04-52306b2b3d00', $academyId->value(), $assignment->id()->value()));
        self::assertNotNull($assignment->deletedAt());
    }
}
