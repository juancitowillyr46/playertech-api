<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository as LegalGuardianRepositoryContract;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Application\Pagination\PaginationQuery;
use App\Shared\Application\Pagination\SortFieldResolver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class LegalGuardianRepository extends ServiceEntityRepository implements LegalGuardianRepositoryContract
{
    private readonly SortFieldResolver $sortFieldResolver;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LegalGuardian::class);
        $this->sortFieldResolver = new SortFieldResolver(
            [
                'created_at' => 'auditTrail.createdAt.value',
                'document_number' => 'documentNumber',
                'first_name' => 'firstName',
                'last_name' => 'lastName',
                'status' => 'status.value',
            ],
            'auditTrail.createdAt.value',
        );
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

    public function findAvailableByPlayer(AcademyId $academyId, PlayerId $playerId, ?string $query = null): array
    {
        $qb = $this->createQueryBuilder('guardian')
            ->where('guardian.academyId = :academyId')
            ->andWhere('guardian.deletedAt IS NULL')
            ->andWhere('NOT EXISTS (
                SELECT 1
                FROM App\Modules\Player\Domain\PlayerGuardian\PlayerGuardian playerGuardian
                WHERE playerGuardian.academyId = :academyId
                  AND playerGuardian.playerId = :playerId
                  AND playerGuardian.guardianId = guardian.id
                  AND playerGuardian.deletedAt IS NULL
            )')
            ->setParameter('academyId', $academyId->value())
            ->setParameter('playerId', $playerId->value())
            ->orderBy('guardian.firstName', 'ASC')
            ->addOrderBy('guardian.lastName', 'ASC');

        if (null !== $query && '' !== trim($query)) {
            $needle = '%'.$this->normalizeSearchText($query).'%';

            $qb->andWhere('(
                LOWER(guardian.firstName) LIKE :query
                OR LOWER(guardian.lastName) LIKE :query
                OR LOWER(CONCAT(guardian.firstName, \' \', guardian.lastName)) LIKE :query
                OR LOWER(guardian.documentNumber) LIKE :query
            )')
                ->setParameter('query', $needle);
        }

        return $qb->getQuery()->getResult();
    }

    public function findAllByAcademy(
        AcademyId $academyId,
        PaginationQuery $pagination,
        ?string $documentNumber = null,
        ?string $documentType = null,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $fullName = null,
    ): array
    {
        $sortField = $this->sortFieldResolver->resolve($pagination->sort);

        $qb = $this->createQueryBuilder('guardian')
            ->where('guardian.academyId = :academyId')
            ->andWhere('guardian.deletedAt IS NULL')
            ->setParameter('academyId', $academyId->value())
            ->orderBy(sprintf('guardian.%s', $sortField), $pagination->direction);

        if (null !== $firstName && '' !== trim($firstName)) {
            $qb->andWhere('LOWER(guardian.firstName) LIKE :firstName')
                ->setParameter('firstName', '%'.$this->normalizeSearchText($firstName).'%');
        }

        if (null !== $lastName && '' !== trim($lastName)) {
            $qb->andWhere('LOWER(guardian.lastName) LIKE :lastName')
                ->setParameter('lastName', '%'.$this->normalizeSearchText($lastName).'%');
        }

        if (null !== $documentNumber && '' !== trim($documentNumber)) {
            $qb->andWhere('LOWER(guardian.documentNumber) = :documentNumber')
                ->setParameter('documentNumber', $this->normalizeSearchText($documentNumber));
        }

        if (null !== $documentType && '' !== trim($documentType)) {
            $qb->andWhere('UPPER(guardian.documentType) = :documentType')
                ->setParameter('documentType', strtoupper(trim($documentType)));
        }

        if (null !== $fullName && '' !== trim($fullName)) {
            $qb->andWhere('(
                LOWER(guardian.firstName) LIKE :fullName
                OR LOWER(guardian.lastName) LIKE :fullName
            )')
                ->setParameter('fullName', '%'.$this->normalizeSearchText($fullName).'%');
        }

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

    private function normalizeSearchText(string $value): string
    {
        $trimmed = trim($value);
        $normalized = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $trimmed);

        return mb_strtolower($normalized !== false ? $normalized : $trimmed);
    }
}
