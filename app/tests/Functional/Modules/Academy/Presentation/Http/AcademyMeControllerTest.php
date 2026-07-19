<?php

declare(strict_types=1);

namespace App\Tests\Functional\Modules\Academy\Presentation\Http;

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
use App\Tests\Support\Database\TestDatabaseKernel;

final class AcademyMeControllerTest extends TestDatabaseKernel
{
    public function testItReturnsTenantContextAndAcademyProfile(): void
    {
        $container = $this->bootTestKernel();
        $entityManager = $this->entityManager($container);
        $jwtManager = $this->jwtManager($container);
        $suffix = bin2hex(random_bytes(4));

        $academy = Academy::create(
            AcademyId::generate(),
            new Name('Academia Test'),
            new Email(sprintf('academy-%s@test.local', $suffix)),
            new PhoneNumber('+51 999 999 999'),
            'Colombia',
            'Cundinamarca',
            'NIT',
            '901234567-8',
            'RESPONSABLE_IVA',
            'facturacion@test.local',
            'signup',
            new Address('Av. Principal 123'),
            new City('Bogota'),
            null,
            AuditTrail::create('system'),
            '8',
        );

        $user = new AccountUser();
        $user->setEmail(sprintf('admin-%s@test.local', $suffix));
        $user->setPasswordHash('hashed-password');
        $user->setAcademyId($academy->id()->value());
        $user->setRole(AccountUser::ROLE_ACADEMY_ADMIN);
        $user->setStatus(AccountUser::STATUS_ACTIVE);
        $user->setFullName('Admin Test');

        $entityManager->persist($academy);
        $entityManager->persist($user);
        $entityManager->flush();

        $token = $jwtManager->create($user);

        $contextResponse = self::$kernel->handle(Request::create(
            '/api/v1/academy/context',
            'GET',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$token,
            ]
        ));

        self::assertSame(200, $contextResponse->getStatusCode());
        $contextPayload = json_decode($contextResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('tenant', $contextPayload['data']['mode']);
        self::assertSame($user->getId(), $contextPayload['data']['userId']);
        self::assertSame($academy->id()->value(), $contextPayload['data']['academyId']);
        self::assertSame(AccountUser::ROLE_ACADEMY_ADMIN, $contextPayload['data']['role']);

        $academyResponse = self::$kernel->handle(Request::create(
            '/api/v1/academy/me',
            'GET',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$token,
            ]
        ));

        self::assertSame(200, $academyResponse->getStatusCode());
        $academyPayload = json_decode($academyResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame($academy->id()->value(), $academyPayload['data']['id']);
        self::assertSame('Academia Test', $academyPayload['data']['name']);
        self::assertSame(sprintf('academy-%s@test.local', $suffix), $academyPayload['data']['contactEmail']);
        self::assertSame('RESPONSABLE_IVA', $academyPayload['data']['taxRegime']);
    }
}
