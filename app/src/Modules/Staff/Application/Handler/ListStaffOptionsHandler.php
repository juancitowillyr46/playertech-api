<?php

declare(strict_types=1);

namespace App\Modules\Staff\Application\Handler;

use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Application\Query\ListStaffOptionsQuery;
use App\Modules\Staff\Application\Response\StaffOptionResponse;
use App\Modules\Staff\Domain\Staff\Staff;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ListStaffOptionsHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return StaffOptionResponse[]
     */
    public function __invoke(ListStaffOptionsQuery $query): array
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('staff.id AS id', 'COALESCE(user.fullName, user.email) AS label')
            ->from(Staff::class, 'staff')
            ->innerJoin(AccountUser::class, 'user', 'WITH', 'user.id = staff.userId')
            ->where('staff.academyId = :academyId')
            ->andWhere('staff.deletedAt IS NULL')
            ->andWhere('user.deletedAt IS NULL')
            ->andWhere('user.status = :status')
            ->setParameter('academyId', $query->academyId->value())
            ->setParameter('status', AccountUser::STATUS_ACTIVE)
            ->orderBy('label', 'ASC');

        if (null !== $query->role) {
            $qb->andWhere('user.role = :role')
                ->setParameter('role', $query->role);
        }

        /** @var array<int, array{id:string,label:string}> $rows */
        $rows = $qb->getQuery()->getArrayResult();

        return array_map(
            static fn (array $row): StaffOptionResponse => StaffOptionResponse::fromRow($row),
            $rows
        );
    }
}
