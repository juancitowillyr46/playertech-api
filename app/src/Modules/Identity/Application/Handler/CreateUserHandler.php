<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Command\CreateUserCommand;
use App\Modules\Identity\Application\Response\UserResponse;
use App\Modules\Identity\Domain\Exception\UserAlreadyExistsException;
use App\Modules\Identity\Domain\Exception\UserTenantScopeViolationException;
use App\Modules\Identity\Domain\Policy\UserAdministrationPolicy;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Shared\Domain\ValueObject\AuditTrail;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

final readonly class CreateUserHandler extends AbstractUserHandler
{
    public function __construct(
        \Doctrine\ORM\EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private UserAdministrationPolicy $userAdministrationPolicy,
    ) {
        parent::__construct($entityManager);
    }

    public function __invoke(CreateUserCommand $command): UserResponse
    {
        $data = $command->input;
        $email = (string) $data->email;
        $role = (string) $data->role;
        $effectiveAcademyId = $command->academyId ?? $data->academyId;

        if (null !== $this->findUserByEmail($email)) {
            throw new UserAlreadyExistsException();
        }

        if (null !== $command->academyId && null !== $data->academyId && $command->academyId !== $data->academyId) {
            throw new UserTenantScopeViolationException();
        }

        $this->userAdministrationPolicy->assertCanCreate($role, $effectiveAcademyId);

        if (null !== $effectiveAcademyId && !Uuid::isValid($effectiveAcademyId)) {
            throw new UserTenantScopeViolationException();
        }

        $user = new AccountUser();
        $user->setFullName($data->fullName);
        $user->setEmail($email);
        $user->setAcademyId($effectiveAcademyId);
        $user->setRole($role);
        $user->setStatus(AccountUser::STATUS_ACTIVE);
        $user->setPasswordHash($this->passwordHasher->hashPassword($user, (string) $data->password));
        $user->setCreatedBy($command->actorId);
        $user->setUpdatedAt(null);
        $user->setUpdatedBy(null);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return UserResponse::fromUser($user);
    }
}
