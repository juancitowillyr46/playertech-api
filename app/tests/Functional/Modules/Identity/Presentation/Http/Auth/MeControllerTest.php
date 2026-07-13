<?php

declare(strict_types=1);

namespace App\Tests\Functional\Modules\Identity\Presentation\Http\Auth;

use App\Modules\Identity\Domain\User\AccountUser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

final class MeControllerTest extends KernelTestCase
{
    public function testItReturnsAuthenticatedUserProfile(): void
    {
        self::ensureKernelShutdown();
        self::bootKernel();

        $container = self::$kernel->getContainer();
        $entityManager = $container->get('doctrine')->getManager();
        $jwtManager = $container->get('lexik_jwt_authentication.jwt_manager');

        $this->resetUsersTable($entityManager);

        $user = new AccountUser();
        $user->setEmail('root@test.local');
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

        self::assertSame('root@test.local', $payload['data']['email']);
        self::assertSame(AccountUser::ROLE_ROOT, $payload['data']['role']);
        self::assertContains(AccountUser::ROLE_ROOT, $payload['data']['roles']);
    }

    private function resetUsersTable(\Doctrine\ORM\EntityManagerInterface $entityManager): void
    {
        $connection = $entityManager->getConnection();
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeStatement('DELETE FROM users');
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
