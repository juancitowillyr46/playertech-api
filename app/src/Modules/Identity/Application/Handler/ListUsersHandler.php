<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Query\ListUsersQuery;
use App\Modules\Identity\Application\Response\UserResponse;
use App\Modules\Identity\Domain\User\AccountUser;

final readonly class ListUsersHandler extends AbstractUserHandler
{
    /**
     * @return UserResponse[]
     */
    public function __invoke(ListUsersQuery $query): array
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(AccountUser::class, 'u')
            ->orderBy('u.fullName', 'ASC')
            ->addOrderBy('u.email', 'ASC');

        if (null !== $query->academyId) {
            $qb->andWhere('u.academyId = :academyId')
                ->setParameter('academyId', $query->academyId);
        }

        /** @var AccountUser[] $users */
        $users = $qb->getQuery()->getResult();

        return array_map(
            static fn (AccountUser $user): UserResponse => UserResponse::fromUser($user),
            $users
        );
    }
}
