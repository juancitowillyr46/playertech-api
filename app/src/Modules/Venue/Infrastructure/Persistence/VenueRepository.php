<?php

declare(strict_types=1);

namespace App\Modules\Venue\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Venue\Domain\Venue\Venue;
use App\Modules\Venue\Domain\Venue\VenueId;
use App\Modules\Venue\Domain\Venue\VenueRepository as VenueRepositoryContract;
use App\Shared\Application\Pagination\PaginationQuery;
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

    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array
    {
        $qb = $this->createQueryBuilder('venue')
            ->andWhere('venue.academyId = :academyId')
            ->setParameter('academyId', $academyId->value())
            ->orderBy(sprintf('venue.%s', $pagination->sort), $pagination->direction);

        $total = (int) (clone $qb)->select('COUNT(venue.id)')->getQuery()->getSingleScalarResult();
        $items = $qb->setFirstResult(($pagination->page - 1) * $pagination->perPage)->setMaxResults($pagination->perPage)->getQuery()->getResult();

        return ['items' => $items, 'total' => $total];
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
