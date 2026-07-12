<?php

declare(strict_types=1);

namespace App\Tests\Functional\Modules\Identity\Presentation\Http\Auth;

use App\Modules\Identity\Domain\User\AccountUser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

final class ProfileControllerTest extends KernelTestCase
{
    public function testItUpdatesOnlyTheCurrentUserName(): void
    {
        self::ensureKernelShutdown();
        self::bootKernel();

        $container = self::$kernel->getContainer();
        $entityManager = $container->get('doctrine')->getManager();
        $jwtManager = $container->get('lexik_jwt_authentication.jwt_manager');

        $this->resetUsersTable($entityManager);

        $user = new AccountUser();
        $user->setEmail('coach@test.local');
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

        $fresh = $entityManager->getRepository(AccountUser::class)->findOneBy(['email' => 'coach@test.local']);
        self::assertSame('Coach New', $fresh?->getFullName());
    }

    private function resetUsersTable(\Doctrine\ORM\EntityManagerInterface $entityManager): void
    {
        $connection = $entityManager->getConnection();
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeStatement('DELETE FROM users');
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
