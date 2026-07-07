<?php

declare(strict_types=1);

namespace App\Modules\Team\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository as TeamRepositoryContract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class TeamRepository extends ServiceEntityRepository implements TeamRepositoryContract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function save(Team $team): void
    {
        $this->getEntityManager()->persist($team);
        $this->getEntityManager()->flush();
    }

    public function findById(AcademyId $academyId, TeamId $teamId): ?Team
    {
        return $this->createQueryBuilder('team')
            ->where('team.id = :teamId')
            ->andWhere('team.academyId = :academyId')
            ->andWhere('team.deletedAt IS NULL')
            ->setParameter('teamId', $teamId->value())
            ->setParameter('academyId', $academyId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Team[]
     */
    public function findAllByAcademy(AcademyId $academyId): array
    {
        return $this->createQueryBuilder('team')
            ->andWhere('team.academyId = :academyId')
            ->andWhere('team.deletedAt IS NULL')
            ->setParameter('academyId', $academyId->value())
            ->orderBy('team.auditTrail.createdAt.value', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
