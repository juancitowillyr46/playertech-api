<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Command\ActivateUserCommand;
use App\Modules\Identity\Application\Response\UserResponse;
use App\Modules\Identity\Domain\User\AccountUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class ActivateUserHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(ActivateUserCommand $command): UserResponse
    {
        if ($command->input->password !== $command->input->passwordConfirmation) {
            throw new BadRequestHttpException('Las contraseñas no coinciden.');
        }

        /** @var AccountUser|null $user */
        $user = $this->entityManager->getRepository(AccountUser::class)->findOneBy([
            'activationToken' => $command->token,
        ]);

        if (!$user instanceof AccountUser) {
            throw new NotFoundHttpException('Token de activación inválido o expirado.');
        }

        if (null !== $user->getActivationExpiresAt() && $user->getActivationExpiresAt() < new \DateTimeImmutable()) {
            throw new NotFoundHttpException('Token de activación inválido o expirado.');
        }

        $user->setPasswordHash($this->passwordHasher->hashPassword($user, (string) $command->input->password));
        $user->activate();
        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->flush();

        return UserResponse::fromUser($user);
    }
}
