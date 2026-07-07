<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Team\Domain\Team;

use App\Modules\Team\Domain\Team\TeamId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class TeamIdTest extends TestCase
{
    public function testItGeneratesValidUuidIdentifiers(): void
    {
        $teamId = TeamId::generate();

        self::assertNotSame('', (string) $teamId);
        self::assertSame((string) $teamId, $teamId->value());
    }

    public function testItRejectsInvalidUuidValues(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new TeamId('invalid-uuid');
    }
}
