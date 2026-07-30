<?php

declare(strict_types=1);

namespace App\Modules\Player\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Document\PlayerDocument;
use App\Modules\Player\Domain\Document\PlayerDocumentId;
use App\Modules\Player\Domain\Document\PlayerDocumentRepository as Contract;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Application\Pagination\PaginationQuery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PlayerDocumentRepository extends ServiceEntityRepository implements Contract
{
    public function __construct(ManagerRegistry $registry) { parent::__construct($registry, PlayerDocument::class); }
    public function save(PlayerDocument $document): void { $this->getEntityManager()->persist($document); $this->getEntityManager()->flush(); }
    public function findActiveByPlayer(AcademyId $academyId, PlayerId $playerId, PaginationQuery $pagination): array
    {
        $qb = $this->createQueryBuilder('document')->where('document.academyId = :academy')->andWhere('document.playerId = :player')->andWhere('document.deletedAt IS NULL')->setParameter('academy', $academyId->value())->setParameter('player', $playerId->value())->orderBy('document.auditTrail.createdAt.value', $pagination->direction);
        $total = (int) (clone $qb)->select('COUNT(document.id)')->getQuery()->getSingleScalarResult();
        return ['items' => $qb->setFirstResult(($pagination->page - 1) * $pagination->perPage)->setMaxResults($pagination->perPage)->getQuery()->getResult(), 'total' => $total];
    }
    public function findActiveById(AcademyId $academyId, PlayerId $playerId, PlayerDocumentId $documentId): ?PlayerDocument
    {
        return $this->createQueryBuilder('document')->where('document.id = :id')->andWhere('document.academyId = :academy')->andWhere('document.playerId = :player')->andWhere('document.deletedAt IS NULL')->setParameter('id', $documentId->value())->setParameter('academy', $academyId->value())->setParameter('player', $playerId->value())->getQuery()->getOneOrNullResult();
    }
}
