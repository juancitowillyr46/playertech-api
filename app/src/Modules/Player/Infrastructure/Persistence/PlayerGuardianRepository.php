<?php

declare(strict_types=1);

namespace App\Modules\Player\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardian;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianId;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianRepository as PlayerGuardianRepositoryContract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PlayerGuardianRepository extends ServiceEntityRepository implements PlayerGuardianRepositoryContract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerGuardian::class);
    }

    public function save(PlayerGuardian $playerGuardian): void
    {
        $this->getEntityManager()->persist($playerGuardian);
        $this->getEntityManager()->flush();
    }

    public function findById(AcademyId $academyId, PlayerGuardianId $playerGuardianId): ?PlayerGuardian
    {
        return $this->createQueryBuilder('playerGuardian')
            ->where('playerGuardian.id = :playerGuardianId')
            ->andWhere('playerGuardian.academyId = :academyId')
            ->andWhere('playerGuardian.deletedAt IS NULL')
            ->setParameter('playerGuardianId', $playerGuardianId->value())
            ->setParameter('academyId', $academyId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByPlayerAndGuardian(AcademyId $academyId, PlayerId $playerId, LegalGuardianId $guardianId): ?PlayerGuardian
    {
        return $this->createQueryBuilder('playerGuardian')
            ->where('playerGuardian.academyId = :academyId')
            ->andWhere('playerGuardian.playerId = :playerId')
            ->andWhere('playerGuardian.guardianId = :guardianId')
            ->andWhere('playerGuardian.deletedAt IS NULL')
            ->setParameter('academyId', $academyId->value())
            ->setParameter('playerId', $playerId->value())
            ->setParameter('guardianId', $guardianId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllByPlayer(AcademyId $academyId, PlayerId $playerId): array
    {
        return $this->createQueryBuilder('playerGuardian')
            ->where('playerGuardian.academyId = :academyId')
            ->andWhere('playerGuardian.playerId = :playerId')
            ->andWhere('playerGuardian.deletedAt IS NULL')
            ->setParameter('academyId', $academyId->value())
            ->setParameter('playerId', $playerId->value())
            ->orderBy('playerGuardian.isPrimary', 'DESC')
            ->addOrderBy('playerGuardian.auditTrail.createdAt.value', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findPrimaryByPlayer(AcademyId $academyId, PlayerId $playerId): ?PlayerGuardian
    {
        return $this->createQueryBuilder('playerGuardian')
            ->where('playerGuardian.academyId = :academyId')
            ->andWhere('playerGuardian.playerId = :playerId')
            ->andWhere('playerGuardian.isPrimary = true')
            ->andWhere('playerGuardian.deletedAt IS NULL')
            ->setParameter('academyId', $academyId->value())
            ->setParameter('playerId', $playerId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }
}
