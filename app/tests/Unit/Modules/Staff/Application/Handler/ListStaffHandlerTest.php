<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Staff\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Application\Handler\ListStaffHandler;
use App\Modules\Staff\Application\Query\ListStaffQuery;
use App\Shared\Application\Pagination\PaginationQuery;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

final class ListStaffHandlerTest extends TestCase
{
    public function testItFiltersCoachesWhenRoleIsProvided(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $connection = $this->createMock(Connection::class);
        $connection->expects(self::once())
            ->method('fetchOne')
            ->with(
                self::stringContains('SELECT COUNT(staff.id)'),
                self::callback(static function (array $params): bool {
                    return '019eec93-9a11-7432-bd04-52306b2b3d8f' === $params['academyId']
                        && AccountUser::ROLE_COACH === $params['role'];
                })
            )
            ->willReturn('1');
        $connection->expects(self::once())
            ->method('fetchAllAssociative')
            ->with(
                self::stringContains('ORDER BY staff.created_at DESC, user.full_name ASC'),
                self::callback(static function (array $params): bool {
                    return 20 === $params['limit']
                        && 0 === $params['offset']
                        && '019eec93-9a11-7432-bd04-52306b2b3d8f' === $params['academyId']
                        && AccountUser::ROLE_COACH === $params['role'];
                }),
                self::callback(static fn (array $types): bool => \Doctrine\DBAL\ParameterType::INTEGER === $types['limit'] && \Doctrine\DBAL\ParameterType::INTEGER === $types['offset'])
            )
            ->willReturn([
            [
                'id' => '019eec93-9a11-7432-bd04-52306b2b3d8d',
                'academyId' => $academyId->value(),
                'userId' => '019eec93-9a11-7432-bd04-52306b2b3d8e',
                'fullName' => 'Juan Perez',
                'email' => 'juan@academiaplayertech.com',
                'role' => AccountUser::ROLE_COACH,
                'status' => 'ACTIVE',
                'userStatus' => AccountUser::STATUS_PENDING_ACTIVATION,
            ],
        ]);

        $handler = new ListStaffHandler($connection);
        $result = $handler(new ListStaffQuery($academyId, new PaginationQuery(), AccountUser::ROLE_COACH));

        self::assertCount(1, $result->items);
        self::assertSame('ROLE_COACH', $result->items[0]->toArray()['role']);
        self::assertSame(AccountUser::STATUS_PENDING_ACTIVATION, $result->items[0]->toArray()['userStatus']);
        self::assertSame(1, $result->meta->total);
    }

    public function testItNormalizesCreatedAtSortToAuditTrailCreatedAt(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');

        $connection = $this->createMock(Connection::class);
        $connection->expects(self::once())
            ->method('fetchOne')
            ->with(self::stringContains('SELECT COUNT(staff.id)'))
            ->willReturn('0');
        $connection->expects(self::once())
            ->method('fetchAllAssociative')
            ->with(
                self::stringContains('ORDER BY staff.created_at DESC, user.full_name ASC'),
                self::callback(static fn (array $params): bool => '019eec93-9a11-7432-bd04-52306b2b3d8f' === $params['academyId']),
                self::anything()
            )
            ->willReturn([]);

        $handler = new ListStaffHandler($connection);
        $handler(new ListStaffQuery($academyId, new PaginationQuery(sort: 'created_at')));

        self::assertTrue(true);
    }
}
