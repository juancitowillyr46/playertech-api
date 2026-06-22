<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Command\DisableUserCommand;
use App\Modules\Identity\Application\Response\UserResponse;
use App\Modules\Identity\Domain\Exception\CannotDisableLastTenantAdminException;
use App\Modules\Identity\Domain\User\AccountUser;

final readonly class DisableUserHandler extends AbstractUserHandler
{
    public function __invoke(DisableUserCommand $command): UserResponse
    {
        $user = $this->requireUser($command->userId, $command->academyId);

        if (null !== $user->getAcademyId() && AccountUser::ROLE_ACADEMY_ADMIN === $user->getRole() && $user->isActive()) {
            if (1 >= $this->countActiveTenantAdmins($user->getAcademyId(), $user->getId())) {
                throw new CannotDisableLastTenantAdminException();
            }
        }

        if (!$user->isActive()) {
            return UserResponse::fromUser($user);
        }

        $user->setStatus(AccountUser::STATUS_INACTIVE);
        $user->setUpdatedAt(new \DateTimeImmutable());
        $user->setUpdatedBy($command->actorId);
        $this->entityManager->flush();

        return UserResponse::fromUser($user);
    }
}
