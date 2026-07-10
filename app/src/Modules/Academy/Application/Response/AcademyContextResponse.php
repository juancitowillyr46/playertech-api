<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Response;

final readonly class AcademyContextResponse
{
    public function __construct(
        private string $mode,
        private ?string $userId,
        private ?string $academyId,
        private ?string $role,
        private array $roles,
    ) {
    }

    public function toArray(): array
    {
        return [
            'mode' => $this->mode,
            'userId' => $this->userId,
            'academyId' => $this->academyId,
            'role' => $this->role,
            'roles' => $this->roles,
        ];
    }
}
