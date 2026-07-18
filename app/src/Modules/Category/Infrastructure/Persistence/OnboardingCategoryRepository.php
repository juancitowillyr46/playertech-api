<?php

declare(strict_types=1);

namespace App\Modules\Category\Infrastructure\Persistence;

use App\Modules\Category\Domain\Category\OnboardingCategory;
use App\Modules\Category\Domain\Category\OnboardingCategoryRepository as OnboardingCategoryRepositoryContract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OnboardingCategoryRepository extends ServiceEntityRepository implements OnboardingCategoryRepositoryContract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OnboardingCategory::class);
    }

    public function findAllActive(): array
    {
        return $this->createQueryBuilder('category')
            ->andWhere('category.status = :status')
            ->setParameter('status', 'ACTIVE')
            ->orderBy('category.code', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findById(string $id): ?OnboardingCategory
    {
        /** @var OnboardingCategory|null $category */
        $category = $this->find($id);

        return $category;
    }
}
