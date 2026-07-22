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
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
        $phone = sprintf('+51 999 %03d %03d', random_int(100, 999), random_int(100, 999));

        $academy = Academy::create(
            AcademyId::generate(),
            new Name('Academia Test'),
            new Email(sprintf('academy-%s@test.local', $suffix)),
            new PhoneNumber($phone),
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

    public function testItUpdatesTenantAcademyProfile(): void
    {
        $container = $this->bootTestKernel();
        $entityManager = $this->entityManager($container);
        $jwtManager = $this->jwtManager($container);
        $suffix = bin2hex(random_bytes(4));
        $phone = sprintf('+51 999 %03d %03d', random_int(100, 999), random_int(100, 999));

        $academy = Academy::create(
            AcademyId::generate(),
            new Name('Academia Test'),
            new Email(sprintf('academy-%s@test.local', $suffix)),
            new PhoneNumber($phone),
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

        $response = self::$kernel->handle(Request::create(
            '/api/v1/academy/me',
            'PUT',
            [],
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$token,
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'name' => 'Club America',
                'contactEmail' => sprintf('academy-%s@test.local', $suffix),
                'phone' => $phone,
                'country' => 'Colombia',
                'department' => 'Risaralda',
                'city' => 'Pereira',
                'address' => 'Dirección 123',
            ], JSON_THROW_ON_ERROR)
        ));

        self::assertSame(200, $response->getStatusCode());

        $payload = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('Club America', $payload['data']['name']);
        self::assertSame($phone, $payload['data']['phone']);
        self::assertSame('Pereira', $payload['data']['city']);
    }

    public function testItUploadsTenantAcademyShield(): void
    {
        $container = $this->bootTestKernel();
        $entityManager = $this->entityManager($container);
        $jwtManager = $this->jwtManager($container);
        $suffix = bin2hex(random_bytes(4));
        $phone = sprintf('+51 999 %03d %03d', random_int(100, 999), random_int(100, 999));

        $academy = Academy::create(
            AcademyId::generate(),
            new Name('Academia Test'),
            new Email(sprintf('academy-%s@test.local', $suffix)),
            new PhoneNumber($phone),
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
        $shieldFile = $this->createPngUpload('academy-shield.png');

        $response = self::$kernel->handle(Request::create(
            '/api/v1/academy/me/shield',
            'POST',
            [],
            [
                'shield' => $shieldFile,
            ],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$token,
            ]
        ));

        self::assertSame(200, $response->getStatusCode());

        $payload = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame($academy->id()->value(), $payload['data']['id']);
        self::assertNotNull($payload['data']['shield']);
        self::assertSame('image/png', $payload['data']['shield']['mimeType']);
        self::assertArrayHasKey('path', $payload['data']['shield']);
        self::assertArrayHasKey('url', $payload['data']['shield']);
        self::assertArrayHasKey('checksum', $payload['data']['shield']);
    }

    public function testItDeletesTenantAcademyShield(): void
    {
        $container = $this->bootTestKernel();
        $entityManager = $this->entityManager($container);
        $jwtManager = $this->jwtManager($container);
        $suffix = bin2hex(random_bytes(4));
        $phone = sprintf('+51 999 %03d %03d', random_int(100, 999), random_int(100, 999));

        $academy = Academy::create(
            AcademyId::generate(),
            new Name('Academia Test'),
            new Email(sprintf('academy-%s@test.local', $suffix)),
            new PhoneNumber($phone),
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
        $shieldFile = $this->createPngUpload('academy-shield.png');

        self::$kernel->handle(Request::create(
            '/api/v1/academy/me/shield',
            'POST',
            [],
            [
                'shield' => $shieldFile,
            ],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$token,
            ]
        ));

        $response = self::$kernel->handle(Request::create(
            '/api/v1/academy/me/shield',
            'DELETE',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$token,
            ]
        ));

        self::assertSame(204, $response->getStatusCode());

        $academyResponse = self::$kernel->handle(Request::create(
            '/api/v1/academy/me',
            'GET',
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer '.$token,
            ]
        ));

        self::assertSame(200, $academyResponse->getStatusCode());
        $academyPayload = json_decode($academyResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertNull($academyPayload['data']['shield']);
    }

    private function createPngUpload(string $filename): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'academy-shield-');
        if (false === $path) {
            self::fail('No se pudo crear un archivo temporal para el escudo.');
        }

        $pngPath = $path.'.png';
        rename($path, $pngPath);

        file_put_contents($pngPath, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO2X8YQAAAAASUVORK5CYII=',
            true
        ));

        return new UploadedFile(
            $pngPath,
            $filename,
            'image/png',
            null,
            true
        );
    }
}
