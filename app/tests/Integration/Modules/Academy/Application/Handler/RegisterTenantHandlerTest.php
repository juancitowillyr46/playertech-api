<?php

declare(strict_types=1);

namespace App\Tests\Integration\Modules\Academy\Application\Handler;

use App\Modules\Academy\Application\Command\RegisterTenantCommand;
use App\Modules\Academy\Application\Dto\TenantSignupInput;
use App\Modules\Academy\Application\Handler\RegisterTenantHandler;
use App\Modules\Academy\Infrastructure\Persistence\AcademyRepository;
use App\Modules\Identity\Domain\User\AccountUser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Shared\Domain\ValueObject\Email;

final class RegisterTenantHandlerTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private RegisterTenantHandler $handler;
    private AcademyRepository $academyRepository;

    protected function setUp(): void
    {
        $this->forceTestEnvironment();
        self::bootKernel();

        $doctrine = self::$kernel->getContainer()->get('doctrine');
        $this->entityManager = $doctrine->getManager();
        $this->academyRepository = new AcademyRepository($doctrine);
        $this->handler = new RegisterTenantHandler(
            $this->academyRepository,
            $this->entityManager,
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
            },
            new class implements MessageBusInterface {
                public function dispatch(object $message, array $stamps = []): Envelope
                {
                    return Envelope::wrap($message);
                }
            },
            'http://localhost:8081',
        );

        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->createSchema($metadata);
    }

    private function forceTestEnvironment(): void
    {
        $databaseUrl = getenv('DATABASE_URL') ?: 'mysql://root:root@mysql:3306/playertech_test?serverVersion=8.0&charset=utf8mb4';

        foreach ([
            'APP_ENV=test',
            'APP_DEBUG=1',
            'KERNEL_CLASS=App\Kernel',
            'DATABASE_URL='.$databaseUrl,
            'MAILER_DSN=null://null',
            'JWT_PASSPHRASE=test_passphrase',
        ] as $value) {
            putenv($value);
            [$name, $envValue] = explode('=', $value, 2);
            $_ENV[$name] = $envValue;
            $_SERVER[$name] = $envValue;
        }
    }

    public function testItRegistersTenantAcademyAndOwnerUser(): void
    {
        $input = new TenantSignupInput(
            'Academia de Prueba',
            'tenant.test@example.com',
            'Juan Perez',
            'secret123',
            '+51 999 999 999',
            'Av. Principal 123',
            'Lima',
            'https://cdn.example.com/logo.png',
        );

        $response = ($this->handler)(new RegisterTenantCommand($input));
        $payload = $response->toArray();

        self::assertSame('Academia de Prueba', $payload['academy']['name']);
        self::assertSame('tenant.test@example.com', $payload['academy']['contact_email']);
        self::assertSame('tenant.test@example.com', $payload['user']['email']);
        self::assertSame(AccountUser::STATUS_PENDING_ACTIVATION, $payload['user']['status']);
        self::assertTrue($payload['user']['activation_pending']);

        $academy = $this->academyRepository->findOneByContactEmail(new Email('tenant.test@example.com'));

        self::assertNotNull($academy);

        /** @var AccountUser|null $user */
        $user = $this->entityManager->getRepository(AccountUser::class)->findOneBy([
            'email' => 'tenant.test@example.com',
        ]);

        self::assertInstanceOf(AccountUser::class, $user);
        self::assertSame($academy?->id()->value(), $user?->getAcademyId());
        self::assertSame(AccountUser::ROLE_ACADEMY_ADMIN, $user?->getRole());
        self::assertSame(AccountUser::STATUS_PENDING_ACTIVATION, $user?->getStatus());
        self::assertNotNull($user?->getActivationToken());
        self::assertNotNull($user?->getActivationExpiresAt());
    }
}
