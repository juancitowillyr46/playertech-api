<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Command\ResendUserInvitationCommand;
use App\Modules\Identity\Application\Message\SendUserInvitationEmailMessage;
use App\Modules\Identity\Application\Response\UserResponse;
use App\Modules\Identity\Domain\User\AccountUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

final readonly class ResendUserInvitationHandler extends AbstractUserHandler
{
    public function __construct(
        EntityManagerInterface $entityManager,
        private MessageBusInterface $messageBus,
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

        $user->markPendingActivation(Uuid::v4()->toRfc4122(), (new \DateTimeImmutable())->modify('+24 hours'));
        $user->setUpdatedAt(new \DateTimeImmutable());
        $user->setUpdatedBy($command->actorId);

        $this->entityManager->flush();

        $activationUrl = sprintf('%s/activate-account/%s', rtrim($this->authFrontendUrl, '/'), $user->getActivationToken());

        $this->messageBus->dispatch(new SendUserInvitationEmailMessage(
            $user->getUserIdentifier(),
            (string) $user->getFullName(),
            $activationUrl
        ));

        return UserResponse::fromUser($user);
    }
}
