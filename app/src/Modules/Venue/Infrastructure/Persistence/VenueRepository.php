<?php

declare(strict_types=1);

namespace App\Modules\Venue\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Venue\Domain\Venue\Venue;
use App\Modules\Venue\Domain\Venue\VenueId;
use App\Modules\Venue\Domain\Venue\VenueRepository as VenueRepositoryContract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class VenueRepository extends ServiceEntityRepository implements VenueRepositoryContract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Venue::class);
    }

    public function save(Venue $venue): void
    {
        $this->getEntityManager()->persist($venue);
        $this->getEntityManager()->flush();
    }

    public function findById(AcademyId $academyId, VenueId $venueId): ?Venue
    {
        return $this->createQueryBuilder('venue')
            ->where('venue.id = :venueId')
            ->andWhere('venue.academyId = :academyId')
            ->setParameter('venueId', $venueId->value())
            ->setParameter('academyId', $academyId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllByAcademy(AcademyId $academyId): array
    {
        return $this->createQueryBuilder('venue')
            ->andWhere('venue.academyId = :academyId')
            ->setParameter('academyId', $academyId->value())
            ->orderBy('venue.auditTrail.createdAt.value', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByAcademyAndId(AcademyId $academyId, VenueId $venueId): ?Venue
    {
        return $this->createQueryBuilder('venue')
            ->andWhere('venue.academyId = :academyId')
            ->andWhere('venue.id = :venueId')
            ->setParameter('academyId', $academyId->value())
            ->setParameter('venueId', $venueId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }
}