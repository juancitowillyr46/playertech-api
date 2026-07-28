<?php

declare(strict_types=1);

namespace App\Modules\Player\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository as PlayerRepositoryContract;
use App\Shared\Application\Pagination\PaginationQuery;
use App\Shared\Application\Pagination\SortFieldResolver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PlayerRepository extends ServiceEntityRepository implements PlayerRepositoryContract
{
    private readonly SortFieldResolver $sortFieldResolver;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
        $this->sortFieldResolver = new SortFieldResolver(
            [
                'created_at' => 'auditTrail.createdAt.value',
                'category_id' => 'categoryId',
                'gender' => 'gender',
                'birth_date' => 'birthDate',
                'document_number' => 'documentNumber',
                'first_name' => 'firstName',
                'last_name' => 'lastName',
                'status' => 'status.value',
            ],
            'auditTrail.createdAt.value',
        );
    }

    public function save(Player $player): void
    {
        $this->getEntityManager()->persist($player);
        $this->getEntityManager()->flush();
    }

    public function findById(AcademyId $academyId, PlayerId $playerId): ?Player
    {
        return $this->createQueryBuilder('player')
            ->where('player.id = :playerId')
            ->andWhere('player.academyId = :academyId')
            ->setParameter('playerId', $playerId->value())
            ->setParameter('academyId', $academyId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByDocumentNumber(AcademyId $academyId, string $documentNumber): ?Player
    {
        return $this->createQueryBuilder('player')
            ->where('player.academyId = :academyId')
            ->andWhere('player.documentNumber = :documentNumber')
            ->setParameter('academyId', $academyId->value())
            ->setParameter('documentNumber', $documentNumber)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllByAcademy(
        AcademyId $academyId,
        PaginationQuery $pagination,
        ?string $gender = null,
        ?string $categoryId = null,
        ?string $createdAtFrom = null,
        ?string $createdAtTo = null,
        ?string $birthDateFrom = null,
        ?string $birthDateTo = null,
    ): array
    {
        $sortField = $this->sortFieldResolver->resolve($pagination->sort);

        $qb = $this->createQueryBuilder('player')
            ->where('player.academyId = :academyId')
            ->setParameter('academyId', $academyId->value())
            ->orderBy(sprintf('player.%s', $sortField), $pagination->direction);

        if (null !== $gender && '' !== trim($gender)) {
            $qb->andWhere('LOWER(player.gender) = :gender')
                ->setParameter('gender', mb_strtolower(trim($gender)));
        }

        if (null !== $categoryId && '' !== trim($categoryId)) {
            $qb->andWhere('player.categoryId = :categoryId')
                ->setParameter('categoryId', trim($categoryId));
        }

        if (null !== $createdAtFrom && '' !== trim($createdAtFrom)) {
            $qb->andWhere('player.auditTrail.createdAt.value >= :createdAtFrom')
                ->setParameter('createdAtFrom', new \DateTimeImmutable(trim($createdAtFrom)));
        }

        if (null !== $createdAtTo && '' !== trim($createdAtTo)) {
            $createdAtToValue = new \DateTimeImmutable(trim($createdAtTo));
            $qb->andWhere('player.auditTrail.createdAt.value <= :createdAtTo')
                ->setParameter('createdAtTo', $createdAtToValue->setTime(23, 59, 59));
        }

        if (null !== $birthDateFrom && '' !== trim($birthDateFrom)) {
            $qb->andWhere('player.birthDate >= :birthDateFrom')
                ->setParameter('birthDateFrom', new \DateTimeImmutable(trim($birthDateFrom)));
        }

        if (null !== $birthDateTo && '' !== trim($birthDateTo)) {
            $birthDateToValue = new \DateTimeImmutable(trim($birthDateTo));
            $qb->andWhere('player.birthDate <= :birthDateTo')
                ->setParameter('birthDateTo', $birthDateToValue->setTime(23, 59, 59));
        }

        $total = (int) (clone $qb)
            ->select('COUNT(player.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $items = $qb
            ->setFirstResult(($pagination->page - 1) * $pagination->perPage)
            ->setMaxResults($pagination->perPage)
            ->getQuery()
            ->getResult();

        return ['items' => $items, 'total' => $total];
    }
}
