<?php

declare(strict_types=1);

namespace App\Tests\Integration\Modules\Identity\Application\Handler;

use App\Modules\Identity\Application\Command\ConfirmPasswordResetCommand;
use App\Modules\Identity\Application\Command\RequestPasswordResetCommand;
use App\Modules\Identity\Application\Dto\ConfirmPasswordResetInput;
use App\Modules\Identity\Application\Dto\RequestPasswordResetInput;
use App\Modules\Identity\Application\Handler\ConfirmPasswordResetHandler;
use App\Modules\Identity\Application\Handler\RequestPasswordResetHandler;
use App\Modules\Identity\Domain\User\AccountUser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Tests\Support\Database\SchemaResetter;

final class PasswordResetHandlerTest extends KernelTestCase
{
    public function testItRequestsAndConfirmsPasswordReset(): void
    {
        self::ensureKernelShutdown();
        self::bootKernel();

        $entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        SchemaResetter::reset($entityManager, [$entityManager->getClassMetadata(AccountUser::class)]);

        $user = new AccountUser();
        $user->setEmail('reset@test.local');
        $user->setPasswordHash('old-hash');
        $user->setFullName('Reset User');
        $user->setStatus(AccountUser::STATUS_ACTIVE);

        $entityManager->persist($user);
        $entityManager->flush();

        $captured = [];
        $messageBus = new class($captured) implements MessageBusInterface {
            public array $messages = [];

            public function __construct(array &$messages)
            {
                $this->messages =& $messages;
            }

            public function dispatch(object $message, array $stamps = []): Envelope
            {
                $this->messages[] = $message;

                return Envelope::wrap($message);
            }
        };

        $requestHandler = new RequestPasswordResetHandler(
            $entityManager,
            $messageBus
        );

        $requestHandler(new RequestPasswordResetCommand(
            new RequestPasswordResetInput('reset@test.local'),
            'http://localhost:8081'
        ));

        $freshUser = $entityManager->getRepository(AccountUser::class)->findOneBy(['email' => 'reset@test.local']);

        self::assertNotNull($freshUser?->getPasswordResetToken());

        $confirmHandler = new ConfirmPasswordResetHandler(
            $entityManager,
            new class implements UserPasswordHasherInterface {
                public function hashPassword(object $user, string $plainPassword): string
                {
                    return 'hashed-'.$plainPassword;
                }

                public function isPasswordValid(object $user, string $plainPassword): bool
                {
                    return true;
                }

                public function needsRehash(object $user): bool
                {
                    return false;
                }
            }
        );

        $confirmHandler(new ConfirmPasswordResetCommand(
            (string) $freshUser?->getPasswordResetToken(),
            new ConfirmPasswordResetInput('new-secret123', 'new-secret123')
        ));

        $updatedUser = $entityManager->getRepository(AccountUser::class)->findOneBy(['email' => 'reset@test.local']);

        self::assertSame('hashed-new-secret123', $updatedUser?->getPassword());
        self::assertNull($updatedUser?->getPasswordResetToken());
    }
}
