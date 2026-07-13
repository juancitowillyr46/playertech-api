<?php

declare(strict_types=1);

namespace App\Modules\Staff\Application\Handler;

use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Application\Query\ListStaffQuery;
use App\Modules\Staff\Application\Response\StaffListItemResponse;
use App\Modules\Staff\Domain\Staff\Staff;
use App\Shared\Application\Pagination\PaginatedResult;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ListStaffHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(ListStaffQuery $query): PaginatedResult
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('staff', 'user')
            ->from(Staff::class, 'staff')
            ->innerJoin(AccountUser::class, 'user', 'WITH', 'user.id = staff.userId')
            ->where('staff.academyId = :academyId')
            ->andWhere('staff.deletedAt IS NULL')
            ->andWhere('user.deletedAt IS NULL')
            ->setParameter('academyId', $query->academyId->value())
            ->orderBy(sprintf('staff.%s', $query->pagination->sort), $query->pagination->direction)
            ->addOrderBy('user.fullName', 'ASC');

        $total = (int) (clone $qb)->select('COUNT(staff.id)')->getQuery()->getSingleScalarResult();
        $rows = $qb
            ->setFirstResult(($query->pagination->page - 1) * $query->pagination->perPage)
            ->setMaxResults($query->pagination->perPage)
            ->getQuery()
            ->getResult();

        $items = array_map(static function (array $row): StaffListItemResponse {
            return StaffListItemResponse::fromEntities($row[0], $row['user']);
        }, $rows);

        return PaginatedResult::fromItems($items, $query->pagination, $total);
    }
}
