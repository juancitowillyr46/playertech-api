<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Command\ActivateUserCommand;
use App\Modules\Identity\Application\Dto\ActivateUserInput;
use App\Modules\Identity\Application\Handler\ActivateUserHandler;
use App\Modules\Identity\Domain\User\AccountUser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ActivateUserHandlerTest extends TestCase
{
    public function testItActivatesAccountWithConfirmedPassword(): void
    {
        $user = new AccountUser();
        $user->setEmail('juan@test.local');
        $user->setPasswordHash('hashed');
        $user->markPendingActivation('token-123', (new \DateTimeImmutable())->modify('+1 day'));

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $repository = $this->createMock(EntityRepository::class);
        $repository->method('findOneBy')->willReturn($user);
        $entityManager->method('getRepository')->willReturn($repository);
        $entityManager->expects(self::once())->method('flush');

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->method('hashPassword')->willReturn('new-hash');

        $handler = new ActivateUserHandler($entityManager, $passwordHasher);
        $response = $handler(new ActivateUserCommand('token-123', new ActivateUserInput('secret123', 'secret123')));

        self::assertSame(AccountUser::STATUS_ACTIVE, $response->toArray()['status']);
    }
}
