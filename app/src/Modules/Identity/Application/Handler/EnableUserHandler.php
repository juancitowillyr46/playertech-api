<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Command\EnableUserCommand;
use App\Modules\Identity\Application\Response\UserResponse;
use App\Modules\Identity\Domain\User\AccountUser;

final readonly class EnableUserHandler extends AbstractUserHandler
{
    public function __invoke(EnableUserCommand $command): UserResponse
    {
        $user = $this->requireUser($command->userId, $command->academyId);

        if ($user->isActive()) {
            return UserResponse::fromUser($user);
        }

        $user->setStatus(AccountUser::STATUS_ACTIVE);
        $user->setUpdatedAt(new \DateTimeImmutable());
        $user->setUpdatedBy($command->actorId);
        $this->entityManager->flush();

        return UserResponse::fromUser($user);
    }
}
