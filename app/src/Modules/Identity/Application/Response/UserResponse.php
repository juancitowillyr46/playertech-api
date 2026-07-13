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
        private array $roles,
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
            $user->getRoles(),
            $user->getRole(),
            $user->getStatus(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'fullName' => $this->fullName,
            'email' => $this->email,
            'academyId' => $this->academyId,
            'roles' => $this->roles,
            'role' => $this->role,
            'status' => $this->status,
        ];
    }
}
