<?php

declare(strict_types=1);

namespace App\Modules\Player\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository as PlayerRepositoryContract;
use App\Shared\Application\Pagination\PaginationQuery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PlayerRepository extends ServiceEntityRepository implements PlayerRepositoryContract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
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

    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array
    {
        $qb = $this->createQueryBuilder('player')
            ->where('player.academyId = :academyId')
            ->setParameter('academyId', $academyId->value())
            ->orderBy(sprintf('player.%s', $pagination->sort), $pagination->direction);

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
