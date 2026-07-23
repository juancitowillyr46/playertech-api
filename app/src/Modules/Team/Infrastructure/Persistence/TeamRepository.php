<?php

declare(strict_types=1);

namespace App\Modules\Team\Infrastructure\Persistence;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository as TeamRepositoryContract;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Application\Pagination\PaginationQuery;
use App\Shared\Application\Pagination\SortFieldResolver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class TeamRepository extends ServiceEntityRepository implements TeamRepositoryContract
{
    private readonly SortFieldResolver $sortFieldResolver;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
        $this->sortFieldResolver = new SortFieldResolver(
            [
                'created_at' => 'auditTrail.createdAt.value',
                'audit_trail.created_at.value' => 'auditTrail.createdAt.value',
                'audittrail.createdat.value' => 'auditTrail.createdAt.value',
                'name' => 'name',
                'category_id' => 'categoryId',
                'categoryid' => 'categoryId',
                'status' => 'status',
            ],
            'auditTrail.createdAt.value',
        );
    }

    public function save(Team $team): void
    {
        $this->getEntityManager()->persist($team);
        $this->getEntityManager()->flush();
    }

    public function findById(AcademyId $academyId, TeamId $teamId): ?Team
    {
        return $this->createQueryBuilder('team')
            ->where('team.id = :teamId')
            ->andWhere('team.academyId = :academyId')
            ->andWhere('team.deletedAt IS NULL')
            ->setParameter('teamId', $teamId->value())
            ->setParameter('academyId', $academyId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByAcademyCategoryAndName(
        AcademyId $academyId,
        CategoryId $categoryId,
        Name $name
    ): ?Team {
        return $this->createQueryBuilder('team')
            ->where('team.academyId = :academyId')
            ->andWhere('team.categoryId = :categoryId')
            ->andWhere('team.name.value = :name')
            ->andWhere('team.deletedAt IS NULL')
            ->setParameter('academyId', $academyId->value())
            ->setParameter('categoryId', $categoryId->value())
            ->setParameter('name', $name->value())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Team[]
     */
    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array
    {
        $sortField = $this->sortFieldResolver->resolve($pagination->sort);

        $qb = $this->createQueryBuilder('team')
            ->andWhere('team.academyId = :academyId')
            ->andWhere('team.deletedAt IS NULL')
            ->setParameter('academyId', $academyId->value())
            ->orderBy(sprintf('team.%s', $sortField), $pagination->direction);

        $total = (int) (clone $qb)->select('COUNT(team.id)')->getQuery()->getSingleScalarResult();
        $items = $qb->setFirstResult(($pagination->page - 1) * $pagination->perPage)->setMaxResults($pagination->perPage)->getQuery()->getResult();

        return ['items' => $items, 'total' => $total];
    }
}
