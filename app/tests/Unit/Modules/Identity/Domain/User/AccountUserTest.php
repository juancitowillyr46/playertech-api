<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Identity\Domain\User;

use App\Modules\Identity\Domain\User\AccountUser;
use PHPUnit\Framework\TestCase;

final class AccountUserTest extends TestCase
{
    public function testItStartsWithRootSafeDefaults(): void
    {
        $user = new AccountUser();

        self::assertSame(AccountUser::DEFAULT_ROLE, $user->getRole());
        self::assertSame(AccountUser::STATUS_ACTIVE, $user->getStatus());
        self::assertTrue($user->isActive());
        self::assertNull($user->getAcademyId());
    }

    public function testFullNameIsTrimmed(): void
    {
        $user = new AccountUser();
        $user->setFullName('  Juan Perez  ');

        self::assertSame('Juan Perez', $user->getFullName());
    }
}
