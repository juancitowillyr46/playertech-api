<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Guardian\Application\Response;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Application\Response\GuardianOptionResponse;
use App\Modules\Guardian\Application\Response\LegalGuardianResponse;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class LegalGuardianResponseTest extends TestCase
{
    public function testItExposesPhoneSingleWithoutCountryPrefix(): void
    {
        $guardian = LegalGuardian::create(
            LegalGuardianId::generate(),
            AcademyId::generate(),
            'Maria',
            'Lopez',
            '+57 312 595 3354',
            'maria@example.com',
            'CC',
            '12345678',
            'Av. Central 123',
            'FATHER',
            AuditTrail::create('actor-id')
        );

        $response = LegalGuardianResponse::fromLegalGuardian($guardian)->toArray();

        self::assertSame('+57 312 595 3354', $response['phone']);
        self::assertSame('3125953354', $response['phoneSingle']);
    }

    public function testItExposesPhoneSingleInGuardianOptions(): void
    {
        $guardian = LegalGuardian::create(
            LegalGuardianId::generate(),
            AcademyId::generate(),
            'Maria',
            'Lopez',
            '+57 312 595 3354',
            'maria@example.com',
            'CC',
            '12345678',
            'Av. Central 123',
            'FATHER',
            AuditTrail::create('actor-id')
        );

        $response = (new GuardianOptionResponse(
            $guardian->id()->value(),
            $guardian->firstName(),
            $guardian->lastName(),
            $guardian->documentNumber(),
            '3125953354',
            'Cédula de ciudadanía',
            'Padre',
        ))->toArray();

        self::assertSame('3125953354', $response['phoneSingle']);
    }
}
