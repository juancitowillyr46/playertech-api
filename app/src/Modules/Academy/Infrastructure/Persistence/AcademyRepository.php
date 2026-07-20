<?php

declare(strict_types=1);

namespace App\Modules\Academy\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Academy\Domain\Academy\AcademyRepository as AcademyRepositoryContract;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\PhoneNumber;
use App\Shared\Application\Pagination\PaginationQuery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class AcademyRepository extends ServiceEntityRepository implements AcademyRepositoryContract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Academy::class);
    }

    public function save(Academy $academy): void
    {
        $this->getEntityManager()->persist($academy);
        $this->getEntityManager()->flush();
    }

    public function findById(AcademyId $academyId): ?Academy
    {
        return $this->find($academyId->value());
    }

    /**
     * @return Academy[]
     */
    public function findAllOrdered(PaginationQuery $pagination): array
    {
        $qb = $this->createQueryBuilder('academy')->orderBy(sprintf('academy.%s', $pagination->sort), $pagination->direction);
        $total = (int) (clone $qb)->select('COUNT(academy.id)')->getQuery()->getSingleScalarResult();
        $items = $qb->setFirstResult(($pagination->page - 1) * $pagination->perPage)->setMaxResults($pagination->perPage)->getQuery()->getResult();
        return ['items' => $items, 'total' => $total];
    }

    public function findOneByContactEmail(Email $contactEmail): ?Academy
    {
        return $this->createQueryBuilder('academy')
            ->andWhere('academy.contactEmail.value = :contactEmail')
            ->setParameter('contactEmail', $contactEmail->value())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByPhone(PhoneNumber $phone): ?Academy
    {
        return $this->createQueryBuilder('academy')
            ->andWhere('academy.phone.value = :phone')
            ->setParameter('phone', $phone->value())
            ->getQuery()
            ->getOneOrNullResult();
    }
}
