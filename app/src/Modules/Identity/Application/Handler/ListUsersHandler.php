<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Query\ListUsersQuery;
use App\Modules\Identity\Application\Response\UserResponse;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Shared\Application\Pagination\PaginatedResult;

final readonly class ListUsersHandler extends AbstractUserHandler
{
    public function __invoke(ListUsersQuery $query): PaginatedResult
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(AccountUser::class, 'u')
            ->orderBy(sprintf('u.%s', $query->pagination->sort), $query->pagination->direction)
            ->addOrderBy('u.email', 'ASC');

        if (null !== $query->academyId) {
            $qb->andWhere('u.academyId = :academyId')
                ->setParameter('academyId', $query->academyId);
        }

        $total = (int) (clone $qb)->select('COUNT(u.id)')->getQuery()->getSingleScalarResult();
        /** @var AccountUser[] $users */
        $users = $qb->setFirstResult(($query->pagination->page - 1) * $query->pagination->perPage)->setMaxResults($query->pagination->perPage)->getQuery()->getResult();

        $items = array_map(
            static fn (AccountUser $user): UserResponse => UserResponse::fromUser($user),
            $users
        );

        return PaginatedResult::fromItems($items, $query->pagination, $total);
    }
}
