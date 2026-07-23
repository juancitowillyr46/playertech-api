<?php

declare(strict_types=1);

namespace App\Modules\Category\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository as CategoryRepositoryContract;
use App\Shared\Application\Pagination\PaginationQuery;
use App\Shared\Application\Pagination\SortFieldResolver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class CategoryRepository extends ServiceEntityRepository implements CategoryRepositoryContract
{
    private readonly SortFieldResolver $sortFieldResolver;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
        $this->sortFieldResolver = new SortFieldResolver(
            [
                'created_at' => 'auditTrail.createdAt.value',
                'audit_trail.created_at.value' => 'auditTrail.createdAt.value',
                'audittrail.createdat.value' => 'auditTrail.createdAt.value',
                'category_key' => 'categoryKey',
                'categorykey' => 'categoryKey',
                'name' => 'name',
                'min_age' => 'minAge',
                'minage' => 'minAge',
                'max_age' => 'maxAge',
                'maxage' => 'maxAge',
                'description' => 'description',
                'status' => 'status',
            ],
            'auditTrail.createdAt.value',
        );
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
            ->andWhere('category.deletedAt IS NULL')
            ->setParameter('categoryId', $categoryId->value())
            ->setParameter('academyId', $academyId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByCategoryKey(AcademyId $academyId, string $categoryKey): ?Category
    {
        return $this->createQueryBuilder('category')
            ->where('category.categoryKey = :categoryKey')
            ->andWhere('category.academyId = :academyId')
            ->andWhere('category.deletedAt IS NULL')
            ->setParameter('categoryKey', strtoupper(trim($categoryKey)))
            ->setParameter('academyId', $academyId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findActiveByAcademy(AcademyId $academyId): array
    {
        return $this->createQueryBuilder('category')
            ->andWhere('category.academyId = :academyId')
            ->andWhere('category.deletedAt IS NULL')
            ->andWhere('category.status = :status')
            ->setParameter('academyId', $academyId->value())
            ->setParameter('status', 'ACTIVE')
            ->orderBy('category.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Category[]
     */
    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array
    {
        $sortField = $this->sortFieldResolver->resolve($pagination->sort);

        $qb = $this->createQueryBuilder('category')
            ->andWhere('category.academyId = :academyId')
            ->andWhere('category.deletedAt IS NULL')
            ->setParameter('academyId', $academyId->value())
            ->orderBy(sprintf('category.%s', $sortField), $pagination->direction);

        $total = (int) (clone $qb)->select('COUNT(category.id)')->getQuery()->getSingleScalarResult();
        $items = $qb->setFirstResult(($pagination->page - 1) * $pagination->perPage)->setMaxResults($pagination->perPage)->getQuery()->getResult();

        return ['items' => $items, 'total' => $total];
    }
}
