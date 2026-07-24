<?php

declare(strict_types=1);

namespace App\Modules\Staff\Application\Handler;

use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Application\Query\ListStaffQuery;
use App\Modules\Staff\Application\Response\StaffListItemResponse;
use App\Shared\Application\Pagination\PaginatedResult;
use App\Shared\Application\Pagination\SortFieldResolver;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

final readonly class ListStaffHandler
{
    private const ALLOWED_SORTS = [
        'created_at' => 'staff.created_at',
        'audit_trail.created_at.value' => 'staff.created_at',
        'audittrail.createdat.value' => 'staff.created_at',
    ];

    public function __construct(
        private Connection $connection,
    ) {
    }

    public function __invoke(ListStaffQuery $query): PaginatedResult
    {
        $sortField = (new SortFieldResolver(self::ALLOWED_SORTS, 'staff.created_at'))->resolve($query->pagination->sort);
        $direction = strtoupper($query->pagination->direction) === 'ASC' ? 'ASC' : 'DESC';
        $offset = ($query->pagination->page - 1) * $query->pagination->perPage;
        [$whereClause, $params] = $this->buildFilters($query);

        $total = $this->countStaff($whereClause, $params);
        $rows = $this->fetchStaffRows($whereClause, $params, $sortField, $direction, $query->pagination->perPage, $offset);

        $items = array_map(static fn (array $row): StaffListItemResponse => StaffListItemResponse::fromRow($row), $rows);

        return PaginatedResult::fromItems($items, $query->pagination, $total);
    }

    /**
     * @return array{0:string,1:array<string, string>}
     */
    private function buildFilters(ListStaffQuery $query): array
    {
        $where = [
            'staff.academy_id = :academyId',
            'staff.deleted_at IS NULL',
            'user.deleted_at IS NULL',
        ];

        $params = [
            'academyId' => $query->academyId->value(),
        ];

        if (null !== $query->role) {
            $where[] = 'user.role = :role';
            $params['role'] = $query->role;
        }

        return [implode(' AND ', $where), $params];
    }

    /**
     * @param array<string, string> $params
     */
    private function countStaff(string $whereClause, array $params): int
    {
        return (int) $this->connection->fetchOne(
            <<<SQL
            SELECT COUNT(staff.id)
            FROM staff staff
            INNER JOIN users user ON user.id = staff.user_id
            WHERE {$whereClause}
            SQL,
            $params
        );
    }

    /**
     * @param array<string, string> $params
     * @return array<int, array<string, mixed>>
     */
    private function fetchStaffRows(
        string $whereClause,
        array $params,
        string $sortField,
        string $direction,
        int $perPage,
        int $offset,
    ): array {
        /** @var array<int, array<string, mixed>> $rows */
        $rows = $this->connection->fetchAllAssociative(
            <<<SQL
            SELECT
                staff.id AS id,
                staff.academy_id AS academyId,
                staff.user_id AS userId,
                user.full_name AS fullName,
                user.email AS email,
                user.role AS role,
                staff.status AS status,
                user.status AS userStatus
            FROM staff staff
            INNER JOIN users user ON user.id = staff.user_id
            WHERE {$whereClause}
            ORDER BY {$sortField} {$direction}, user.full_name ASC
            LIMIT :limit OFFSET :offset
            SQL,
            $params + [
                'limit' => $perPage,
                'offset' => $offset,
            ],
            [
                'limit' => ParameterType::INTEGER,
                'offset' => ParameterType::INTEGER,
            ]
        );

        return $rows;
    }
}
