<?php

declare(strict_types=1);

namespace App\Tests\Functional\Modules\Academy\Presentation\Http\Open;

use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use App\Tests\Support\Database\TestDatabaseKernel;
use Symfony\Component\HttpFoundation\Request;

final class TenantAvailabilityControllerTest extends TestDatabaseKernel
{
    public function testItReportsTakenEmailAndPhone(): void
    {
        $container = $this->bootTestKernel();
        $entityManager = $this->entityManager($container);
        $suffix = bin2hex(random_bytes(4));

        $academy = Academy::create(
            AcademyId::generate(),
            new Name('Academia Test'),
            new Email(sprintf('academy-%s@test.local', $suffix)),
            new PhoneNumber('+51 999 111 222'),
            'Colombia',
            'Cundinamarca',
            null,
            null,
            null,
            null,
            'signup',
            new Address('Av. Principal 123'),
            new City('Bogota'),
            null,
            AuditTrail::create('system'),
            '8',
        );

        $entityManager->persist($academy);
        $entityManager->flush();

        $response = self::$kernel->handle(Request::create(
            '/api/v1/public/tenants/availability',
            'GET',
            [
                'contactEmail' => sprintf('academy-%s@test.local', $suffix),
                'phone' => '+51 999 111 222',
            ]
        ));

        self::assertSame(200, $response->getStatusCode());

        $payload = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertFalse($payload['data']['contactEmailAvailable']);
        self::assertFalse($payload['data']['phoneAvailable']);
    }
}
