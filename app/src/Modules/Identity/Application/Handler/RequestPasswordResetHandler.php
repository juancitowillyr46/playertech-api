<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Command\RequestPasswordResetCommand;
use App\Modules\Identity\Application\Message\SendPasswordResetEmailMessage;
use App\Modules\Identity\Domain\User\AccountUser;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

final readonly class RequestPasswordResetHandler extends AbstractUserHandler
{
    public function __construct(
        \Doctrine\ORM\EntityManagerInterface $entityManager,
        private MessageBusInterface $messageBus,
        private string $authFrontendUrl,
    ) {
        parent::__construct($entityManager);
    }

    public function __invoke(RequestPasswordResetCommand $command): void
    {
        $email = trim((string) $command->input->email);

        if ('' === $email) {
            return;
        }

        /** @var AccountUser|null $user */
        $user = $this->entityManager->getRepository(AccountUser::class)->findOneBy(['email' => $email]);

        if (!$user instanceof AccountUser || !$user->isActive()) {
            return;
        }

        $token = Uuid::v4()->toRfc4122();
        $expiresAt = (new \DateTimeImmutable())->modify('+2 hours');

        $user->markPasswordResetRequested($token, $expiresAt);
        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->flush();

        $resetUrl = sprintf('%s/auth/reset-password?token=%s', rtrim($this->authFrontendUrl, '/'), $token);

        $this->messageBus->dispatch(new SendPasswordResetEmailMessage(
            $user->getUserIdentifier(),
            (string) $user->getFullName(),
            $resetUrl,
        ));
    }
}
