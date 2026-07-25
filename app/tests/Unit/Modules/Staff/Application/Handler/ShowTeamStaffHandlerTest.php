<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Staff\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Application\Handler\ShowTeamStaffHandler;
use App\Modules\Staff\Application\Query\ShowTeamStaffQuery;
use App\Modules\Staff\Domain\Staff\Staff;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Modules\Staff\Domain\TeamStaffAssignment\StaffRole;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignment;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignmentId;
use App\Modules\Team\Domain\Team\TeamId;
use App\Shared\Domain\ValueObject\AuditTrail;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
final class ShowTeamStaffHandlerTest extends TestCase
{
    public function testItListsTeamStaff(): void
    {
        $staffRepository = new InMemoryStaffRepository();
        $assignmentRepository = new InMemoryTeamStaffAssignmentRepository();
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $userId = '019eec93-9a11-7432-bd04-52306b2b3d8e';
        $staff = Staff::create(StaffId::generate(), $academyId, $userId, AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d00'));
        $staffRepository->save($staff);
        $assignment = TeamStaffAssignment::create(TeamStaffAssignmentId::generate(), $academyId, $staff->id(), new TeamId('019eec93-9a11-7432-bd04-52306b2b3d88'), new StaffRole(StaffRole::HEAD_COACH), AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d00'));
        $assignmentRepository->save($assignment);

        $user = new AccountUser();
        $user->setId($userId);
        $user->setFullName('Juan Perez');
        $user->setEmail('juan@test.local');
        $user->setAcademyId($academyId->value());

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $userRepository = $this->createMock(EntityRepository::class);
        $userRepository->method('find')->with($userId)->willReturn($user);
        $entityManager->method('getRepository')->willReturn($userRepository);

        $handler = new ShowTeamStaffHandler($staffRepository, $assignmentRepository, $entityManager);
        $items = $handler(new ShowTeamStaffQuery($academyId, new TeamId('019eec93-9a11-7432-bd04-52306b2b3d88')));
        self::assertCount(1, $items);
        self::assertSame(StaffRole::HEAD_COACH, $items[0]->toArray()['role']);
        self::assertSame('Entrenador principal', $items[0]->toArray()['roleName']);
        self::assertSame('Juan Perez', $items[0]->toArray()['fullName']);
    }
}
