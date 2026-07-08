<?php

declare(strict_types=1);

namespace App\Modules\Membership\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Membership\Domain\Membership\Membership;
use App\Modules\Membership\Domain\Membership\MembershipRepository as MembershipRepositoryContract;
use App\Modules\Player\Domain\Player\PlayerId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class MembershipRepository extends ServiceEntityRepository implements MembershipRepositoryContract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Membership::class);
    }

    public function save(Membership $membership): void
    {
        $this->getEntityManager()->persist($membership);
        $this->getEntityManager()->flush();
    }

    public function findActiveByPlayerId(AcademyId $academyId, PlayerId $playerId): ?Membership
    {
        return $this->createQueryBuilder('membership')
            ->where('membership.academyId = :academyId')
            ->andWhere('membership.playerId = :playerId')
            ->andWhere('membership.status = :status')
            ->setParameter('academyId', $academyId->value())
            ->setParameter('playerId', $playerId->value())
            ->setParameter('status', 'active')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
