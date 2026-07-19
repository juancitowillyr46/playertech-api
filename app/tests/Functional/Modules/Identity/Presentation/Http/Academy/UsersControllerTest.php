<?php

declare(strict_types=1);

namespace App\Tests\Functional\Modules\Identity\Presentation\Http\Academy;

use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Support\Database\SchemaResetter;
use App\Tests\Support\Database\TestDatabaseKernel;

final class UsersControllerTest extends TestDatabaseKernel
{
    public function testItInvitesUsersAndListsThemForTenant(): void
    {
        $container = $this->bootTestKernel();
        $entityManager = $this->entityManager($container);
        $jwtManager = $this->jwtManager($container);
        SchemaResetter::reset($entityManager, [
            $entityManager->getClassMetadata(Academy::class),
            $entityManager->getClassMetadata(AccountUser::class),
        ]);

        $academy = Academy::create(
            AcademyId::generate(),
            new Name('Academia Test'),
            new Email('academy@test.local'),
            new PhoneNumber('+51 999 999 999'),
            'Colombia',
            'Lima',
            null,
            null,
            null,
            null,
            'signup',
            new Address('Av. Principal 123'),
            new City('Lima'),
            null,
            AuditTrail::create('system'),
        );

        $admin = new AccountUser();
        $admin->setEmail('admin@test.local');
        $admin->setPasswordHash('hashed-password');
        $admin->setAcademyId($academy->id()->value());
        $admin->setRole(AccountUser::ROLE_ACADEMY_ADMIN);
        $admin->setStatus(AccountUser::STATUS_ACTIVE);
        $admin->setFullName('Admin Test');

        $entityManager->persist($academy);
        $entityManager->persist($admin);
        $entityManager->flush();

        $jwtToken = $jwtManager->create($admin);

        $inviteResponse = self::$kernel->handle(Request::create(
            '/api/v1/academy/users/invite',
            'POST',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$jwtToken,
                'CONTENT_TYPE' => 'application/json',
            ],
            content: json_encode([
                'fullName' => 'Juan Perez',
                'email' => 'juan@test.local',
                'role' => AccountUser::ROLE_ACADEMY_ADMIN,
            ], JSON_THROW_ON_ERROR)
        ));

        self::assertSame(201, $inviteResponse->getStatusCode());
        $invitePayload = json_decode($inviteResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame(AccountUser::STATUS_PENDING_ACTIVATION, $invitePayload['data']['status']);

        $listResponse = self::$kernel->handle(Request::create(
            '/api/v1/academy/users',
            'GET',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$jwtToken,
            ]
        ));

        self::assertSame(200, $listResponse->getStatusCode());
        $listPayload = json_decode($listResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertCount(2, $listPayload['data']);
    }
}
