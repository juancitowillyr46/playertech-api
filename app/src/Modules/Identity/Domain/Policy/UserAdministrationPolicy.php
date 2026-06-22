<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Policy;

use App\Modules\Identity\Domain\Exception\CannotDisableLastTenantAdminException;
use App\Modules\Identity\Domain\Exception\UserTenantScopeViolationException;
use App\Modules\Identity\Domain\User\AccountUser;

final class UserAdministrationPolicy
{
    public function assertCanCreate(string $role, ?string $academyId): void
    {
        if (AccountUser::ROLE_ROOT === $role && null !== $academyId) {
            throw new UserTenantScopeViolationException();
        }

        if (AccountUser::ROLE_ROOT !== $role && (null === $academyId || '' === $academyId)) {
            throw new UserTenantScopeViolationException();
        }
    }

    public function assertCanChangeRole(AccountUser $user, string $newRole): void
    {
        if (AccountUser::ROLE_ROOT === $user->getRole() && AccountUser::ROLE_ROOT !== $newRole) {
            throw new UserTenantScopeViolationException();
        }

        if (AccountUser::ROLE_ROOT !== $user->getRole() && AccountUser::ROLE_ROOT === $newRole) {
            throw new UserTenantScopeViolationException();
        }
    }

    public function assertCanDisable(AccountUser $user, int $activeTenantAdmins): void
    {
        if (null !== $user->getAcademyId() && AccountUser::ROLE_ACADEMY_ADMIN === $user->getRole() && $user->isActive() && 1 >= $activeTenantAdmins) {
            throw new CannotDisableLastTenantAdminException();
        }
    }
}
