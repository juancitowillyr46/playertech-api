<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Identity\Infrastructure\Security;

use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Identity\Infrastructure\Security\UserChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

final class UserCheckerTest extends TestCase
{
    public function testItRejectsPendingActivationUsersBeforeAuthentication(): void
    {
        $user = new AccountUser();
        $user->setEmail('juan@test.local');
        $user->setPasswordHash('hashed');
        $user->setStatus(AccountUser::STATUS_PENDING_ACTIVATION);

        $checker = new UserChecker();

        $this->expectException(CustomUserMessageAccountStatusException::class);
        $this->expectExceptionMessage('Cuenta pendiente de activación.');

        $checker->checkPreAuth($user);
    }

    public function testItRejectsInactiveUsersBeforeAuthentication(): void
    {
        $user = new AccountUser();
        $user->setEmail('juan@test.local');
        $user->setPasswordHash('hashed');
        $user->setStatus(AccountUser::STATUS_INACTIVE);

        $checker = new UserChecker();

        $this->expectException(CustomUserMessageAccountStatusException::class);
        $this->expectExceptionMessage('Usuario inactivo.');

        $checker->checkPreAuth($user);
    }
}
