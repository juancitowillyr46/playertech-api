<?php

declare(strict_types=1);

namespace App\Modules\Staff\Application\Handler;

use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Application\Query\ListStaffOptionsQuery;
use App\Modules\Staff\Application\Response\StaffOptionResponse;
use App\Modules\Staff\Domain\Staff\Staff;
use App\Modules\Staff\Domain\TeamStaffAssignment\StaffRole;
use App\Modules\Staff\Domain\TeamStaffAssignment\StaffRoleLabelCatalog;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignment;
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
        if ('roles' === strtolower((string) $query->type)) {
            return [
                StaffOptionResponse::fromRow(['id' => StaffRole::HEAD_COACH, 'label' => StaffRoleLabelCatalog::label(StaffRole::HEAD_COACH)]),
                StaffOptionResponse::fromRow(['id' => StaffRole::ASSISTANT_COACH, 'label' => StaffRoleLabelCatalog::label(StaffRole::ASSISTANT_COACH)]),
                StaffOptionResponse::fromRow(['id' => StaffRole::GOALKEEPER_COACH, 'label' => StaffRoleLabelCatalog::label(StaffRole::GOALKEEPER_COACH)]),
                StaffOptionResponse::fromRow(['id' => StaffRole::PHYSICAL_PREPARER, 'label' => StaffRoleLabelCatalog::label(StaffRole::PHYSICAL_PREPARER)]),
                StaffOptionResponse::fromRow(['id' => StaffRole::NUTRITIONIST, 'label' => StaffRoleLabelCatalog::label(StaffRole::NUTRITIONIST)]),
                StaffOptionResponse::fromRow(['id' => StaffRole::PHYSIOTHERAPY, 'label' => StaffRoleLabelCatalog::label(StaffRole::PHYSIOTHERAPY)]),
            ];
        }

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

        if (null !== $query->teamId) {
            /** @var array<int, array{staffId:string}> $assignedRows */
            $assignedRows = $this->entityManager->createQueryBuilder()
                ->select('assignment.staffId AS staffId')
                ->from(TeamStaffAssignment::class, 'assignment')
                ->where('assignment.academyId = :academyId')
                ->andWhere('assignment.teamId = :teamId')
                ->andWhere('assignment.deletedAt IS NULL')
                ->setParameter('academyId', $query->academyId->value())
                ->setParameter('teamId', $query->teamId)
                ->getQuery()
                ->getArrayResult();

            $assignedStaffIds = array_values(array_filter(array_map(
                static fn (array $row): string => (string) ($row['staffId'] ?? ''),
                $assignedRows
            )));

            if ($assignedStaffIds !== []) {
                $qb->andWhere('staff.id NOT IN (:assignedStaffIds)')
                    ->setParameter('assignedStaffIds', $assignedStaffIds);
            }
        }

        /** @var array<int, array{id:string,label:string}> $rows */
        $rows = $qb->getQuery()->getArrayResult();

        return array_map(
            static fn (array $row): StaffOptionResponse => StaffOptionResponse::fromRow($row),
            $rows
        );
    }
}
