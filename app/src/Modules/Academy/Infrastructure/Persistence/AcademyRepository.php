<?php

declare(strict_types=1);

namespace App\Modules\Academy\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\Academy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class AcademyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Academy::class);
    }

    /**
     * @return Academy[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('academy')
            ->orderBy('academy.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByContactEmail(string $contactEmail): ?Academy
    {
        return $this->findOneBy(['contactEmail' => $contactEmail]);
    }
}
