<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Command\ResendUserInvitationCommand;
use App\Modules\Identity\Application\Message\SendUserInvitationEmailMessage;
use App\Modules\Identity\Application\Message\SendUserPasswordAndActivationEmailMessage;
use App\Modules\Identity\Application\Response\UserResponse;
use App\Modules\Identity\Domain\User\AccountUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

final readonly class ResendUserInvitationHandler extends AbstractUserHandler
{
    public function __construct(
        EntityManagerInterface $entityManager,
        private MessageBusInterface $messageBus,
        private UserPasswordHasherInterface $passwordHasher,
        private string $authFrontendUrl,
    ) {
        parent::__construct($entityManager);
    }

    public function __invoke(ResendUserInvitationCommand $command): UserResponse
    {
        $user = $this->requireUser($command->userId, $command->academyId);

        if (AccountUser::STATUS_PENDING_ACTIVATION !== $user->getStatus() || null === $user->getActivationToken()) {
            throw new BadRequestHttpException('El usuario no tiene una invitación pendiente.');
        }

        $now = new \DateTimeImmutable();
        $user->markPendingActivation(Uuid::v4()->toRfc4122(), $now->modify('+24 hours'));
        $user->setUpdatedAt($now);
        $user->setUpdatedBy($command->actorId);

        $this->entityManager->flush();

        $activationUrl = sprintf('%s/activate-account/%s', rtrim($this->authFrontendUrl, '/'), $user->getActivationToken());

        if ('PASSWORD' === strtoupper($command->mode)) {
            $password = $this->generatePassword();
            $user->setPasswordHash($this->passwordHasher->hashPassword($user, $password));
            $this->entityManager->flush();

            $this->messageBus->dispatch(new SendUserPasswordAndActivationEmailMessage(
                $user->getUserIdentifier(),
                (string) $user->getFullName(),
                $user->getUserIdentifier(),
                $password,
                $activationUrl
            ));
        } else {
            $this->messageBus->dispatch(new SendUserInvitationEmailMessage(
                $user->getUserIdentifier(),
                (string) $user->getFullName(),
                $activationUrl
            ));
        }

        return UserResponse::fromUser($user);
    }

    private function generatePassword(): string
    {
        return sprintf(
            '%s%s%s',
            strtoupper(bin2hex(random_bytes(2))),
            bin2hex(random_bytes(3)),
            strtoupper(bin2hex(random_bytes(2)))
        );
    }
}
