<?php

declare(strict_types=1);

namespace App\Modules\Staff\Application\Response;

use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Domain\Staff\Staff;

final readonly class StaffDetailResponse
{
    private function __construct(
        private string $id,
        private string $academyId,
        private string $userId,
        private ?string $fullName,
        private string $email,
        private string $role,
        private string $status,
        private string $userStatus,
        private string $accessMode,
    ) {
    }

    public static function fromStaffAndUser(Staff $staff, AccountUser $user): self
    {
        return new self(
            $staff->id()->value(),
            $staff->academyId()->value(),
            $staff->userId(),
            $user->getFullName(),
            $user->getUserIdentifier(),
            $user->getRole(),
            $staff->status()->value(),
            $user->getStatus(),
            self::resolveAccessMode($user),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'academyId' => $this->academyId,
            'userId' => $this->userId,
            'fullName' => $this->fullName,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
            'userStatus' => $this->userStatus,
            'accessMode' => $this->accessMode,
        ];
    }

    private static function resolveAccessMode(AccountUser $user): string
    {
        return AccountUser::STATUS_PENDING_ACTIVATION === $user->getStatus()
            && null !== $user->getActivationToken()
            ? 'INVITATION'
            : 'PASSWORD';
    }
}
