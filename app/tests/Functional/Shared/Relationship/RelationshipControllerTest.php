<?php

declare(strict_types=1);

namespace App\Tests\Functional\Shared\Relationship;

use App\Modules\Academy\Domain\Academy\{Academy, AcademyId};
use App\Modules\Identity\Domain\User\AccountUser;
use App\Shared\Domain\ValueObject\{Address, AuditTrail, City, Email, Name, PhoneNumber};
use App\Tests\Support\Database\{SchemaResetter, TestDatabaseKernel};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class RelationshipControllerTest extends TestDatabaseKernel
{
    private EntityManagerInterface $entityManager;
    private string $token;

    protected function setUp(): void
    {
        $container = $this->bootTestKernel();
        $this->entityManager = $this->entityManager($container);
        SchemaResetter::reset($this->entityManager, array_map(
            fn (string $class) => $this->entityManager->getClassMetadata($class),
            [Academy::class, AccountUser::class]
        ));

        $academy = Academy::create(
            AcademyId::generate(),
            new Name('Academia Catalogos'),
            new Email('catalog@test.local'),
            new PhoneNumber('+57 300 111 2233'),
            'Colombia',
            'Cundinamarca',
            null,
            null,
            null,
            null,
            'signup',
            new Address('Calle 1'),
            new City('Bogota'),
            null,
            AuditTrail::create('system'),
        );
        $user = new AccountUser();
        $user->setEmail('catalog-admin@test.local');
        $user->setPasswordHash('hash');
        $user->setAcademyId($academy->id()->value());
        $user->setRole(AccountUser::ROLE_COACH);
        $user->setStatus(AccountUser::STATUS_ACTIVE);
        $user->setFullName('Catalog User');
        $this->entityManager->persist($academy);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->token = $this->jwtManager($container)->create($user);
    }

    public function testItReturnsTheCatalogForAnAuthenticatedTenantUser(): void
    {
        $response = self::$kernel->handle(Request::create(
            '/api/v1/academy/relationships/options',
            'GET',
            server: ['HTTP_AUTHORIZATION' => 'Bearer ' . $this->token],
        ));
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame(200, $response->getStatusCode());
        self::assertCount(8, $body['data']);
        self::assertSame('FATHER', $body['data'][0]['value']);
        self::assertSame([], $body['meta']);
    }

    public function testItRejectsAnUnauthenticatedRequest(): void
    {
        $response = self::$kernel->handle(Request::create('/api/v1/academy/relationships/options', 'GET'));

        self::assertSame(401, $response->getStatusCode());
    }
}
