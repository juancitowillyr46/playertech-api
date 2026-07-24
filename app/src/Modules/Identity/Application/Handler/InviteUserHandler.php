<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Command\InviteUserCommand;
use App\Modules\Identity\Application\Message\SendUserInvitationEmailMessage;
use App\Modules\Identity\Application\Response\UserResponse;
use App\Modules\Identity\Domain\Exception\UserAlreadyExistsException;
use App\Modules\Identity\Domain\Policy\UserAdministrationPolicy;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Shared\Domain\ValueObject\AuditTrail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

final readonly class InviteUserHandler extends AbstractUserHandler
{
    public function __construct(
        EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private UserAdministrationPolicy $userAdministrationPolicy,
        private MessageBusInterface $messageBus,
        private string $authFrontendUrl,
    ) {
        parent::__construct($entityManager);
    }

    public function __invoke(InviteUserCommand $command): UserResponse
    {
        $data = $command->input;
        $email = (string) $data->email;
        $role = (string) $data->role;

        if (null !== $this->findUserByEmail($email)) {
            throw new UserAlreadyExistsException();
        }

        $this->userAdministrationPolicy->assertCanCreate($role, $command->academyId);

        if (!Uuid::isValid($command->academyId)) {
            throw new \App\Modules\Identity\Domain\Exception\UserTenantScopeViolationException();
        }

        $user = new AccountUser();
        $user->setFullName($data->fullName);
        $user->setEmail($email);
        $user->setAcademyId($command->academyId);
        $user->setRole($role);
        $user->setStatus(AccountUser::STATUS_PENDING_ACTIVATION);
        $user->setPasswordHash($this->passwordHasher->hashPassword($user, Uuid::v4()->toRfc4122()));
        $user->markPendingActivation(Uuid::v4()->toRfc4122(), (new \DateTimeImmutable())->modify('+24 hours'));
        $user->setCreatedBy($command->actorId);
        $user->setUpdatedAt(null);
        $user->setUpdatedBy(null);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $activationUrl = sprintf('%s/activate-account/%s', rtrim($this->authFrontendUrl, '/'), $user->getActivationToken());

        $this->messageBus->dispatch(new SendUserInvitationEmailMessage(
            $email,
            (string) $data->fullName,
            $activationUrl
        ));

        return UserResponse::fromUser($user);
    }
}
