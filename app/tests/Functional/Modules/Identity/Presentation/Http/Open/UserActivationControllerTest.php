<?php

declare(strict_types=1);

namespace App\Tests\Functional\Modules\Identity\Presentation\Http\Open;

use App\Modules\Identity\Domain\User\AccountUser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

final class UserActivationControllerTest extends KernelTestCase
{
    public function testItActivatesAPendingUser(): void
    {
        self::ensureKernelShutdown();
        self::bootKernel();

        $container = self::$kernel->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema([
            $entityManager->getClassMetadata(AccountUser::class),
        ]);
        $schemaTool->createSchema([
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
