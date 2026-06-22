<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Command\DisableUserCommand;
use App\Modules\Identity\Application\Response\UserResponse;
use App\Modules\Identity\Domain\Policy\UserAdministrationPolicy;
use App\Modules\Identity\Domain\User\AccountUser;

final readonly class DisableUserHandler extends AbstractUserHandler
{
    public function __construct(
        \Doctrine\ORM\EntityManagerInterface $entityManager,
        private UserAdministrationPolicy $userAdministrationPolicy,
    ) {
        parent::__construct($entityManager);
    }

    public function __invoke(DisableUserCommand $command): UserResponse
    {
        $user = $this->requireUser($command->userId, $command->academyId);

        $this->userAdministrationPolicy->assertCanDisable(
            $user,
            null === $user->getAcademyId() ? 0 : $this->countActiveTenantAdmins($user->getAcademyId(), $user->getId())
        );

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
