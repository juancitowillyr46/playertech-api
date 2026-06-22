<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Academy\Domain\Academy;

use App\Modules\Academy\Domain\Academy\AcademyId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class AcademyIdTest extends TestCase
{
    public function testItGeneratesValidUuidIdentifiers(): void
    {
        $academyId = AcademyId::generate();

        self::assertNotSame('', (string) $academyId);
        self::assertSame((string) $academyId, $academyId->value());
    }

    public function testItRejectsInvalidUuidValues(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new AcademyId('invalid-uuid');
    }
}
