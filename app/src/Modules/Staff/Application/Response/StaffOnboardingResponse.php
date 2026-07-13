<?php

declare(strict_types=1);

namespace App\Modules\Staff\Application\Response;

use App\Modules\Identity\Application\Response\UserResponse;

final readonly class StaffOnboardingResponse
{
    public function __construct(
        public UserResponse $user,
        public StaffResponse $staff,
        public string $accessMode,
    ) {
    }

    public function toArray(): array
    {
        return [
            'user' => $this->user->toArray(),
            'staff' => $this->staff->toArray(),
            'accessMode' => $this->accessMode,
        ];
    }
}
