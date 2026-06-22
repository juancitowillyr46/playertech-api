<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Response;

use App\Modules\Identity\Domain\User\AccountUser;

final readonly class UserResponse
{
    private function __construct(
        private string $id,
        private ?string $fullName,
        private string $email,
        private ?string $academyId,
        private string $role,
        private string $status,
    ) {
    }

    public static function fromUser(AccountUser $user): self
    {
        return new self(
            $user->getId(),
            $user->getFullName(),
            $user->getUserIdentifier(),
            $user->getAcademyId(),
            $user->getRole(),
            $user->getStatus(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->fullName,
            'email' => $this->email,
            'academy_id' => $this->academyId,
            'role' => $this->role,
            'status' => $this->status,
        ];
    }
}
