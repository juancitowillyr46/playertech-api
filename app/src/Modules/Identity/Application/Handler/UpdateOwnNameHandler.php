<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Command\UpdateOwnNameCommand;
use App\Modules\Identity\Application\Response\UserResponse;

final readonly class UpdateOwnNameHandler extends AbstractUserHandler
{
    public function __invoke(UpdateOwnNameCommand $command): UserResponse
    {
        $user = $this->requireUser($command->actorId);

        $user->setFullName($command->input->fullName);
        $user->setUpdatedAt(new \DateTimeImmutable());
        $user->setUpdatedBy($command->actorId);

        $this->entityManager->flush();

        return UserResponse::fromUser($user);
    }
}
