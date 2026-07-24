<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Command\InviteUserCommand;
use App\Modules\Identity\Application\Dto\InviteUserInput;
use App\Modules\Identity\Application\Handler\InviteUserHandler;
use App\Modules\Identity\Domain\Policy\UserAdministrationPolicy;
use App\Modules\Identity\Domain\User\AccountUser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class InviteUserHandlerTest extends TestCase
{
    public ?object $lastMessage = null;

    public function testItCreatesPendingInvitationAndDispatchesEmail(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $repository = $this->createMock(EntityRepository::class);
        $repository->method('findOneBy')->willReturn(null);
        $entityManager->method('getRepository')->willReturn($repository);
        $entityManager->expects(self::once())->method('persist')->with(self::isInstanceOf(AccountUser::class));
        $entityManager->expects(self::once())->method('flush');

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->method('hashPassword')->willReturn('hashed');

        $messageBus = new class($this) implements MessageBusInterface {
            public function __construct(private InviteUserHandlerTest $test)
            {
            }

            public function dispatch(object $message, array $stamps = []): Envelope
            {
                $this->test->lastMessage = $message;
                return new Envelope($message);
            }
        };

        $handler = new InviteUserHandler(
            $entityManager,
            $passwordHasher,
            new UserAdministrationPolicy(),
            $messageBus,
            'http://localhost:4200'
        );

        $response = $handler(new InviteUserCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d00',
            new InviteUserInput('Juan Perez', 'juan@test.local', AccountUser::ROLE_ACADEMY_ADMIN, '019eec93-9a11-7432-bd04-52306b2b3d8f'),
            '019eec93-9a11-7432-bd04-52306b2b3d8f'
        ));

        self::assertSame('juan@test.local', $response->toArray()['email']);
        self::assertSame(AccountUser::STATUS_PENDING_ACTIVATION, $response->toArray()['status']);
        self::assertNotNull($this->lastMessage);
        self::assertStringStartsWith('http://localhost:4200/activate-account/', $this->lastMessage->activationUrl);
    }
}
