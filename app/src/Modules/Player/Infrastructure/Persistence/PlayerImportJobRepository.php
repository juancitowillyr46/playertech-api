<?php

declare(strict_types=1);

namespace App\Modules\Player\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Import\PlayerImportJob;
use App\Modules\Player\Domain\Import\PlayerImportJobId;
use App\Modules\Player\Domain\Import\PlayerImportJobRepository as PlayerImportJobRepositoryContract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PlayerImportJobRepository extends ServiceEntityRepository implements PlayerImportJobRepositoryContract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerImportJob::class);
    }

    public function save(PlayerImportJob $job): void
    {
        $this->getEntityManager()->persist($job);
        $this->getEntityManager()->flush();
    }

    public function findById(AcademyId $academyId, PlayerImportJobId $jobId): ?PlayerImportJob
    {
        return $this->createQueryBuilder('job')
            ->where('job.id = :jobId')
            ->andWhere('job.academyId = :academyId')
            ->andWhere('job.deletedAt IS NULL')
            ->setParameter('jobId', $jobId->value())
            ->setParameter('academyId', $academyId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }
}
