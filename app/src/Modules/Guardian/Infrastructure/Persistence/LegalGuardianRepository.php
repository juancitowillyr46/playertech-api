<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository as LegalGuardianRepositoryContract;
use App\Shared\Application\Pagination\PaginationQuery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class LegalGuardianRepository extends ServiceEntityRepository implements LegalGuardianRepositoryContract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LegalGuardian::class);
    }

    public function save(LegalGuardian $guardian): void
    {
        $this->getEntityManager()->persist($guardian);
        $this->getEntityManager()->flush();
    }

    public function findById(AcademyId $academyId, LegalGuardianId $guardianId): ?LegalGuardian
    {
        return $this->createQueryBuilder('guardian')
            ->where('guardian.id = :guardianId')
            ->andWhere('guardian.academyId = :academyId')
            ->andWhere('guardian.deletedAt IS NULL')
            ->setParameter('guardianId', $guardianId->value())
            ->setParameter('academyId', $academyId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByEmail(AcademyId $academyId, string $email): ?LegalGuardian
    {
        return $this->createQueryBuilder('guardian')
            ->where('guardian.academyId = :academyId')
            ->andWhere('LOWER(guardian.email) = LOWER(:email)')
            ->andWhere('guardian.deletedAt IS NULL')
            ->setParameter('academyId', $academyId->value())
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array
    {
        $qb = $this->createQueryBuilder('guardian')
            ->where('guardian.academyId = :academyId')
            ->andWhere('guardian.deletedAt IS NULL')
            ->setParameter('academyId', $academyId->value())
            ->orderBy(sprintf('guardian.%s', $pagination->sort), $pagination->direction);

        $total = (int) (clone $qb)
            ->select('COUNT(guardian.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $items = $qb
            ->setFirstResult(($pagination->page - 1) * $pagination->perPage)
            ->setMaxResults($pagination->perPage)
            ->getQuery()
            ->getResult();

        return [
            'items' => $items,
            'total' => $total,
        ];
    }
}
