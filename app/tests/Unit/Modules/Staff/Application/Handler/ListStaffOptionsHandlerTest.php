<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Staff\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Application\Handler\ListStaffOptionsHandler;
use App\Modules\Staff\Application\Query\ListStaffOptionsQuery;
use App\Modules\Staff\Domain\Staff\Staff;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Modules\Staff\Domain\TeamStaffAssignment\StaffRole;
use App\Shared\Domain\ValueObject\AuditTrail;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

final class ListStaffOptionsHandlerTest extends TestCase
{
    public function testItReturnsOnlyActiveStaffAsOptions(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');

        $query = $this->createMock(Query::class);
        $query->method('getArrayResult')->willReturn([
            ['id' => '019eec93-9a11-7432-bd04-52306b2b3d8e', 'label' => 'Juan Perez'],
        ]);

        $qb = $this->createMock(QueryBuilder::class);
        $andWhereCalls = [];
        $setParameterCalls = [];

        $qb->method('select')->willReturnSelf();
        $qb->method('from')->willReturnSelf();
        $qb->method('innerJoin')->willReturnSelf();
        $qb->method('where')->willReturnSelf();
        $qb->method('andWhere')->willReturnCallback(function (string $condition) use (&$andWhereCalls, $qb) {
            $andWhereCalls[] = $condition;

            return $qb;
        });
        $qb->method('setParameter')->willReturnCallback(function (string $name, mixed $value) use (&$setParameterCalls, $qb) {
            $setParameterCalls[$name] = $value;

            return $qb;
        });
        $qb->method('orderBy')->willReturnSelf();
        $qb->method('getQuery')->willReturn($query);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('createQueryBuilder')->willReturn($qb);

        $handler = new ListStaffOptionsHandler($entityManager);
        $result = $handler(new ListStaffOptionsQuery($academyId, AccountUser::ROLE_COACH));

        self::assertCount(1, $result);
        self::assertSame('Juan Perez', $result[0]->toArray()['label']);
        self::assertContains('user.role = :role', $andWhereCalls);
        self::assertSame(AccountUser::ROLE_COACH, $setParameterCalls['role']);
        self::assertSame(AccountUser::STATUS_ACTIVE, $setParameterCalls['status']);
    }

    public function testItReturnsTechnicalRolesAsOptions(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::never())->method('createQueryBuilder');

        $handler = new ListStaffOptionsHandler($entityManager);
        $result = $handler(new ListStaffOptionsQuery($academyId, null, 'roles'));

        self::assertCount(6, $result);
        self::assertSame(StaffRole::HEAD_COACH, $result[0]->toArray()['id']);
        self::assertSame('Entrenador principal', $result[0]->toArray()['label']);
        self::assertSame(StaffRole::PHYSIOTHERAPY, $result[5]->toArray()['id']);
        self::assertSame('Fisioterapia', $result[5]->toArray()['label']);
    }

    public function testItCanFilterOutStaffAlreadyAssignedToATeam(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $teamId = '8b7eecc0-f228-42fd-8f63-5728080b8282';

        $query = $this->createMock(Query::class);
        $query->method('getArrayResult')->willReturn([]);

        $staffQb = $this->createMock(QueryBuilder::class);
        $staffQb->method('select')->willReturnSelf();
        $staffQb->method('from')->willReturnSelf();
        $staffQb->method('innerJoin')->willReturnSelf();
        $staffQb->method('where')->willReturnSelf();
        $staffQb->method('andWhere')->willReturnSelf();
        $staffQb->method('setParameter')->willReturnSelf();
        $staffQb->method('orderBy')->willReturnSelf();
        $staffQb->method('getQuery')->willReturn($query);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('createQueryBuilder')->willReturn($staffQb);

        $handler = new ListStaffOptionsHandler($entityManager);
        $result = $handler(new ListStaffOptionsQuery($academyId, AccountUser::ROLE_COACH, null, $teamId));

        self::assertCount(0, $result);
    }
}
