<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Response;

final readonly class AcademyContextView
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
            'user_id' => $this->userId,
            'academy_id' => $this->academyId,
            'role' => $this->role,
            'roles' => $this->roles,
        ];
    }
}
