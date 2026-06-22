<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Command\UpdateUserCommand;
use App\Modules\Identity\Application\Response\UserResponse;
use App\Modules\Identity\Domain\Exception\UserAlreadyExistsException;
use App\Modules\Identity\Domain\Exception\UserTenantScopeViolationException;
use App\Modules\Identity\Domain\User\AccountUser;

final readonly class UpdateUserHandler extends AbstractUserHandler
{
    public function __invoke(UpdateUserCommand $command): UserResponse
    {
        $user = $this->requireUser($command->userId, $command->academyId);
        $data = $command->input;
        $email = (string) $data->email;
        $role = (string) $data->role;

        $existingUser = $this->findUserByEmail($email, $user->getId());

        if (null !== $existingUser) {
            throw new UserAlreadyExistsException();
        }

        if (AccountUser::ROLE_ROOT === $user->getRole() && AccountUser::ROLE_ROOT !== $role) {
            throw new UserTenantScopeViolationException();
        }

        if (AccountUser::ROLE_ROOT !== $user->getRole() && AccountUser::ROLE_ROOT === $role) {
            throw new UserTenantScopeViolationException();
        }

        if (null !== $user->getAcademyId() && (null === $command->academyId || $user->getAcademyId() !== $command->academyId)) {
            throw new UserTenantScopeViolationException();
        }

        $user->setFullName($data->fullName);
        $user->setEmail($email);
        $user->setRole($role);
        $user->setUpdatedAt(new \DateTimeImmutable());
        $user->setUpdatedBy($command->actorId);

        $this->entityManager->flush();

        return UserResponse::fromUser($user);
    }
}
