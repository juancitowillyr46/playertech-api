<?php

declare(strict_types=1);

namespace App\Tests\Functional\Modules\Identity\Presentation\Http\Open;

use App\Modules\Identity\Domain\User\AccountUser;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Support\Database\SchemaResetter;
use App\Tests\Support\Database\TestDatabaseKernel;

final class UserActivationControllerTest extends TestDatabaseKernel
{
    public function testItActivatesAPendingUser(): void
    {
        $container = $this->bootTestKernel();
        $entityManager = $this->entityManager($container);
        SchemaResetter::reset($entityManager, [
            $entityManager->getClassMetadata(AccountUser::class),
        ]);

        $user = new AccountUser();
        $user->setEmail('juan@test.local');
        $user->setPasswordHash('hashed');
        $user->setStatus(AccountUser::STATUS_PENDING_ACTIVATION);
        $user->markPendingActivation('activation-token-123', (new \DateTimeImmutable())->modify('+1 day'));

        $entityManager->persist($user);
        $entityManager->flush();

        $response = self::$kernel->handle(Request::create(
            '/api/v1/public/users/activate/activation-token-123',
            'POST',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
            ], JSON_THROW_ON_ERROR)
        ));

        self::assertSame(200, $response->getStatusCode());
        $payload = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame(AccountUser::STATUS_ACTIVE, $payload['data']['status']);
    }
}
