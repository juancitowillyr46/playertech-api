<?php

declare(strict_types=1);

namespace App\Modules\Category\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository as CategoryRepositoryContract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class CategoryRepository extends ServiceEntityRepository implements CategoryRepositoryContract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function save(Category $category): void
    {
        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();
    }

    public function findById(AcademyId $academyId, CategoryId $categoryId): ?Category
    {
        return $this->createQueryBuilder('category')
            ->where('category.id = :categoryId')
            ->andWhere('category.academyId = :academyId')
            ->setParameter('categoryId', $categoryId->value())
            ->setParameter('academyId', $academyId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Category[]
     */
    public function findAllByAcademy(AcademyId $academyId): array
    {
        return $this->createQueryBuilder('category')
            ->andWhere('category.academyId = :academyId')
            ->andWhere('category.deletedAt IS NULL')
            ->setParameter('academyId', $academyId->value())
            ->orderBy('category.auditTrail.createdAt.value', 'DESC')
            ->getQuery()
            ->getResult();
    }

}