<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Domain\Player;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class PlayerTest extends TestCase
{
    public function testItNormalizesColombianPhoneNumbersToInternationalFormat(): void
    {
        $player = Player::create(
            new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d80'),
            new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f'),
            'CC',
            'Juan',
            'Rodas',
            new \DateTimeImmutable('1989-09-04'),
            '1088329031',
            null,
            '3125953354',
            null,
            null,
            null,
            null,
            null,
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e')
        );

        self::assertSame('+573125953354', $player->phone());
    }

    public function testItAcceptsPhoneNumbersWrittenWithTheCountryCodePrefix(): void
    {
        $player = Player::create(
            new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d81'),
            new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f'),
            'CC',
            'Juan',
            'Rodas',
            new \DateTimeImmutable('1989-09-04'),
            '1088329031',
            null,
            '+57 3125953354',
            null,
            null,
            null,
            null,
            null,
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e')
        );

        self::assertSame('+573125953354', $player->phone());
    }

    public function testItRejectsInvalidPhoneNumbers(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('phone must be a valid Colombian mobile number.');

        Player::create(
            new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d82'),
            new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f'),
            'CC',
            'Juan',
            'Rodas',
            new \DateTimeImmutable('1989-09-04'),
            '1088329031',
            null,
            '12345',
            null,
            null,
            null,
            null,
            null,
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e')
        );
    }
}
