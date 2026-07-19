<?php

declare(strict_types=1);

namespace App\Tests\Functional\Modules\Identity\Presentation\Http\Auth;

use App\Modules\Identity\Domain\User\AccountUser;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Support\Database\TestDatabaseKernel;

final class ProfileControllerTest extends TestDatabaseKernel
{
    public function testItUpdatesOnlyTheCurrentUserName(): void
    {
        $container = $this->bootTestKernel();
        $entityManager = $this->entityManager($container);
        $jwtManager = $this->jwtManager($container);

        $email = 'coach-'.uniqid('', true).'@test.local';
        $user = new AccountUser();
        $user->setEmail($email);
        $user->setPasswordHash('hashed-password');
        $user->setRole(AccountUser::ROLE_ROOT);
        $user->setFullName('Coach Old');

        $entityManager->persist($user);
        $entityManager->flush();

        $response = self::$kernel->handle(Request::create(
            '/api/v1/auth/me/name',
            'PUT',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$jwtManager->create($user),
                'CONTENT_TYPE' => 'application/json',
            ],
            content: json_encode([
                'fullName' => 'Coach New',
            ], JSON_THROW_ON_ERROR)
        ));

        self::assertSame(200, $response->getStatusCode());

        $payload = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('Coach New', $payload['data']['fullName']);

        $fresh = $entityManager->getRepository(AccountUser::class)->findOneBy(['email' => $email]);
        self::assertSame('Coach New', $fresh?->getFullName());
    }

    public function testItRequestsPasswordResetForAuthenticatedUser(): void
    {
        $container = $this->bootTestKernel();
        $entityManager = $this->entityManager($container);
        $jwtManager = $this->jwtManager($container);

        $email = 'recover-'.uniqid('', true).'@test.local';
        $user = new AccountUser();
        $user->setEmail($email);
        $user->setPasswordHash('hashed-password');
        $user->setRole(AccountUser::ROLE_ROOT);
        $user->setFullName('Recover User');
        $user->setStatus(AccountUser::STATUS_ACTIVE);

        $entityManager->persist($user);
        $entityManager->flush();

        $response = self::$kernel->handle(Request::create(
            '/api/v1/auth/me/password-reset/request',
            'POST',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$jwtManager->create($user),
            ]
        ));

        self::assertSame(200, $response->getStatusCode());

        $fresh = $entityManager->getRepository(AccountUser::class)->findOneBy(['email' => $email]);
        self::assertNotNull($fresh?->getPasswordResetToken());
        self::assertNotNull($fresh?->getPasswordResetExpiresAt());
    }
}
