<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Academy\Domain\Academy;

use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Academy\Domain\Academy\AcademyRegistrationSource;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class AcademyTaxProfileTest extends TestCase
{
    public function testItUpdatesTaxProfile(): void
    {
        $academy = Academy::create(
            AcademyId::generate(),
            new Name('Academia PlayerTech'),
            new Email('contacto@example.com'),
            null,
            'Colombia',
            'Cundinamarca',
            null,
            null,
            null,
            null,
            AcademyRegistrationSource::platform()->value(),
            new Address('Av. Principal 123'),
            new City('Bogota'),
            null,
            AuditTrail::create('actor-id'),
        );

        $academy->updateTaxProfile(
            'NIT',
            '901234567-8',
            'RESPONSABLE_IVA',
            'facturacion@example.com',
            'actor-id',
        );

        self::assertSame('NIT', $academy->taxIdType());
        self::assertSame('901234567-8', $academy->taxIdNumber());
        self::assertSame('RESPONSABLE_IVA', $academy->taxRegime());
        self::assertSame('facturacion@example.com', $academy->billingEmail());
    }
}
