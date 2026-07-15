<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignment;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentId;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentRepository as Contract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class TeamAssignmentRepository extends ServiceEntityRepository implements Contract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamAssignment::class);
    }

    public function save(TeamAssignment $assignment): void
    {
        $this->getEntityManager()->persist($assignment);
        $this->getEntityManager()->flush();
    }

    public function findById(AcademyId $academyId, TeamAssignmentId $assignmentId): ?TeamAssignment
    {
        return $this->createQueryBuilder('assignment')
            ->where('assignment.id = :assignmentId')
            ->andWhere('assignment.academyId = :academyId')
            ->andWhere('assignment.deletedAt IS NULL')
            ->setParameter('assignmentId', $assignmentId->value())
            ->setParameter('academyId', $academyId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findActiveByPlayerAndTeam(AcademyId $academyId, PlayerId $playerId, TeamId $teamId): ?TeamAssignment
    {
        return $this->createQueryBuilder('assignment')
            ->where('assignment.academyId = :academyId')
            ->andWhere('assignment.playerId = :playerId')
            ->andWhere('assignment.teamId = :teamId')
            ->andWhere('assignment.endDate IS NULL')
            ->andWhere('assignment.deletedAt IS NULL')
            ->setParameter('academyId', $academyId->value())
            ->setParameter('playerId', $playerId->value())
            ->setParameter('teamId', $teamId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findPrimaryByPlayer(AcademyId $academyId, PlayerId $playerId): ?TeamAssignment
    {
        return $this->createQueryBuilder('assignment')
            ->where('assignment.academyId = :academyId')
            ->andWhere('assignment.playerId = :playerId')
            ->andWhere('assignment.isPrimary = true')
            ->andWhere('assignment.endDate IS NULL')
            ->andWhere('assignment.deletedAt IS NULL')
            ->setParameter('academyId', $academyId->value())
            ->setParameter('playerId', $playerId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findActiveByPlayerExcept(AcademyId $academyId, PlayerId $playerId, ?TeamAssignmentId $excludedAssignmentId = null): ?TeamAssignment
    {
        $qb = $this->createQueryBuilder('assignment')
            ->where('assignment.academyId = :academyId')
            ->andWhere('assignment.playerId = :playerId')
            ->andWhere('assignment.endDate IS NULL')
            ->andWhere('assignment.deletedAt IS NULL')
            ->setParameter('academyId', $academyId->value())
            ->setParameter('playerId', $playerId->value())
            ->orderBy('assignment.startDate', 'DESC');

        if (null !== $excludedAssignmentId) {
            $qb->andWhere('assignment.id != :excludedAssignmentId')
               ->setParameter('excludedAssignmentId', $excludedAssignmentId->value());
        }

        return $qb->setMaxResults(1)->getQuery()->getOneOrNullResult();
    }

    public function findAllByPlayer(AcademyId $academyId, PlayerId $playerId): array
    {
        return $this->createQueryBuilder('assignment')
            ->where('assignment.academyId = :academyId')
            ->andWhere('assignment.playerId = :playerId')
            ->andWhere('assignment.deletedAt IS NULL')
            ->setParameter('academyId', $academyId->value())
            ->setParameter('playerId', $playerId->value())
            ->orderBy('assignment.startDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
