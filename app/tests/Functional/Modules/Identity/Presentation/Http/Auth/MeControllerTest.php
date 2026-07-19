<?php

declare(strict_types=1);

namespace App\Tests\Functional\Modules\Identity\Presentation\Http\Auth;

use App\Modules\Identity\Domain\User\AccountUser;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Support\Database\TestDatabaseKernel;

final class MeControllerTest extends TestDatabaseKernel
{
    public function testItReturnsAuthenticatedUserProfile(): void
    {
        $container = $this->bootTestKernel();
        $entityManager = $this->entityManager($container);
        $jwtManager = $this->jwtManager($container);

        $email = 'root-'.uniqid('', true).'@test.local';
        $user = new AccountUser();
        $user->setEmail($email);
        $user->setPasswordHash('hashed-password');
        $user->setRole(AccountUser::ROLE_ROOT);
        $user->setFullName('Root Admin');

        $entityManager->persist($user);
        $entityManager->flush();

        $response = self::$kernel->handle(Request::create(
            '/api/v1/auth/me',
            'GET',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$jwtManager->create($user),
            ]
        ));

        self::assertSame(200, $response->getStatusCode());

        $payload = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame($email, $payload['data']['email']);
        self::assertSame(AccountUser::ROLE_ROOT, $payload['data']['role']);
        self::assertContains(AccountUser::ROLE_ROOT, $payload['data']['roles']);
    }
}
