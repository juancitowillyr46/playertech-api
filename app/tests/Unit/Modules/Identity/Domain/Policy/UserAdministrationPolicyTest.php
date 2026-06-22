<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Identity\Domain\Policy;

use App\Modules\Identity\Domain\Exception\CannotDisableLastTenantAdminException;
use App\Modules\Identity\Domain\Exception\UserTenantScopeViolationException;
use App\Modules\Identity\Domain\Policy\UserAdministrationPolicy;
use App\Modules\Identity\Domain\User\AccountUser;
use PHPUnit\Framework\TestCase;

final class UserAdministrationPolicyTest extends TestCase
{
    private UserAdministrationPolicy $policy;

    protected function setUp(): void
    {
        $this->policy = new UserAdministrationPolicy();
    }

    public function testRootCannotBeCreatedWithAcademy(): void
    {
        $this->expectException(UserTenantScopeViolationException::class);

        $this->policy->assertCanCreate(AccountUser::ROLE_ROOT, '019eec93-9a11-7432-bd04-52306b2b3d8e');
    }

    public function testTenantUserMustHaveAcademy(): void
    {
        $this->expectException(UserTenantScopeViolationException::class);

        $this->policy->assertCanCreate(AccountUser::ROLE_ACADEMY_ADMIN, null);
    }

    public function testRootRoleCannotBeAssignedToTenantUser(): void
    {
        $user = new AccountUser();
        $user->setRole(AccountUser::ROLE_ACADEMY_ADMIN);

        $this->expectException(UserTenantScopeViolationException::class);

        $this->policy->assertCanChangeRole($user, AccountUser::ROLE_ROOT);
    }

    public function testTenantRoleCannotBeRemovedFromRootUser(): void
    {
        $user = new AccountUser();
        $user->setRole(AccountUser::ROLE_ROOT);

        $this->expectException(UserTenantScopeViolationException::class);

        $this->policy->assertCanChangeRole($user, AccountUser::ROLE_ACADEMY_ADMIN);
    }

    public function testCannotDisableLastTenantAdmin(): void
    {
        $user = new AccountUser();
        $user->setRole(AccountUser::ROLE_ACADEMY_ADMIN);
        $user->setAcademyId('019eec93-9a11-7432-bd04-52306b2b3d8e');

        $this->expectException(CannotDisableLastTenantAdminException::class);

        $this->policy->assertCanDisable($user, 1);
    }
}
