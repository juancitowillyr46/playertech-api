<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Staff\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Application\Handler\ShowStaffHandler;
use App\Modules\Staff\Application\Query\ShowStaffQuery;
use App\Modules\Staff\Domain\Staff\Staff;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Modules\Staff\Domain\Staff\StaffRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

final class ShowStaffHandlerTest extends TestCase
{
    public function testItReturnsStaffDetailWithInvitationAccessMode(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $userId = '019eec93-9a11-7432-bd04-52306b2b3d8e';

        $staff = Staff::create(StaffId::generate(), $academyId, $userId, AuditTrail::create('system'));

        $user = new AccountUser();
        $user->setId($userId);
        $user->setEmail('juan@test.local');
        $user->setFullName('Juan Perez');
        $user->setAcademyId($academyId->value());
        $user->setRole(AccountUser::ROLE_COACH);
        $user->setStatus(AccountUser::STATUS_PENDING_ACTIVATION);
        $user->markPendingActivation('activation-token-123', (new \DateTimeImmutable())->modify('+1 day'));

        $staffRepository = $this->createMock(StaffRepository::class);
        $staffRepository->method('findByUserId')->with($academyId, $userId)->willReturn($staff);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $userRepository = $this->createMock(EntityRepository::class);
        $userRepository->method('find')->with($userId)->willReturn($user);
        $entityManager->method('getRepository')->willReturn($userRepository);

        $handler = new ShowStaffHandler($staffRepository, $entityManager);
        $result = $handler(new ShowStaffQuery($academyId, $userId));

        self::assertSame($userId, $result->toArray()['userId']);
        self::assertSame('INVITATION', $result->toArray()['accessMode']);
        self::assertSame(AccountUser::STATUS_PENDING_ACTIVATION, $result->toArray()['userStatus']);
    }
}
