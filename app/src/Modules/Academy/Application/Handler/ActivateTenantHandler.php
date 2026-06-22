<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Handler;

use App\Modules\Academy\Application\Response\TenantActivationResponse;
use App\Modules\Academy\Application\Command\ActivateTenantCommand;
use App\Modules\Identity\Domain\User\AccountUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class ActivateTenantHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(ActivateTenantCommand $command): TenantActivationResponse
    {
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

        $user->activate();
        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->flush();

        return new TenantActivationResponse(
            $user->getUserIdentifier(),
            $user->getStatus(),
            true,
        );
    }
}
