<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Command\ResendUserInvitationCommand;
use App\Modules\Identity\Application\Handler\ResendUserInvitationHandler;
use App\Modules\Identity\Domain\User\AccountUser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class ResendUserInvitationHandlerTest extends TestCase
{
    public function testItResendsInvitationOnlyForPendingUsers(): void
    {
        $user = new AccountUser();
        $user->setEmail('juan@test.local');
        $user->setFullName('Juan Perez');
        $user->setAcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $user->setStatus(AccountUser::STATUS_PENDING_ACTIVATION);
        $user->markPendingActivation('token-123', (new \DateTimeImmutable())->modify('+1 day'));

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $repository = $this->createMock(EntityRepository::class);
        $repository->method('find')->with('019eec93-9a11-7432-bd04-52306b2b3d8e')->willReturn($user);
        $entityManager->method('getRepository')->willReturn($repository);
        $entityManager->expects(self::once())->method('flush');

        $messageBus = new class implements MessageBusInterface {
            public function dispatch(object $message, array $stamps = []): Envelope
            {
                return new Envelope($message);
            }
        };

        $handler = new ResendUserInvitationHandler($entityManager, $messageBus, 'http://localhost:4200');
        $response = $handler(new ResendUserInvitationCommand('actor-id', '019eec93-9a11-7432-bd04-52306b2b3d8e', '019eec93-9a11-7432-bd04-52306b2b3d8f'));

        self::assertSame(AccountUser::STATUS_PENDING_ACTIVATION, $response->toArray()['status']);
    }
}
