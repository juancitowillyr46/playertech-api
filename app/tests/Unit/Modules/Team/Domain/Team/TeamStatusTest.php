<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Team\Domain\Team;

use App\Modules\Team\Domain\Team\TeamStatus;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class TeamStatusTest extends TestCase
{
    public function testItAcceptsOnlyConfiguredStates(): void
    {
        self::assertTrue(TeamStatus::active()->isActive());
        self::assertTrue(TeamStatus::inactive()->isInactive());
    }

    public function testItRejectsInvalidStatusValues(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new TeamStatus('BROKEN');
    }
}
