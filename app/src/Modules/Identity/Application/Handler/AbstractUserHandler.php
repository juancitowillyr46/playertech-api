<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Handler;

use App\Modules\Identity\Domain\User\AccountUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

abstract readonly class AbstractUserHandler
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }

    protected function requireUser(string $userId, ?string $academyId = null): AccountUser
    {
        if (!Uuid::isValid($userId)) {
            throw new NotFoundHttpException('Usuario no encontrado.');
        }

        /** @var AccountUser|null $user */
        $user = $this->entityManager->getRepository(AccountUser::class)->find($userId);

        if (!$user instanceof AccountUser) {
            throw new NotFoundHttpException('Usuario no encontrado.');
        }

        if (null !== $academyId && $user->getAcademyId() !== $academyId) {
            throw new NotFoundHttpException('Usuario no encontrado.');
        }

        return $user;
    }

    protected function findUserByEmail(string $email, ?string $excludeUserId = null): ?AccountUser
    {
        $criteria = ['email' => $email];

        /** @var AccountUser|null $user */
        $user = $this->entityManager->getRepository(AccountUser::class)->findOneBy($criteria);

        if (!$user instanceof AccountUser) {
            return null;
        }

        if (null !== $excludeUserId && $user->getId() === $excludeUserId) {
            return null;
        }

        return $user;
    }

    protected function countActiveTenantAdmins(string $academyId, ?string $excludeUserId = null): int
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('COUNT(u.id)')
            ->from(AccountUser::class, 'u')
            ->where('u.academyId = :academyId')
            ->andWhere('u.role = :role')
            ->andWhere('u.status = :status')
            ->setParameter('academyId', $academyId)
            ->setParameter('role', AccountUser::ROLE_ACADEMY_ADMIN)
            ->setParameter('status', AccountUser::STATUS_ACTIVE);

        if (null !== $excludeUserId) {
            $qb->andWhere('u.id != :excludeUserId')
                ->setParameter('excludeUserId', $excludeUserId);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
