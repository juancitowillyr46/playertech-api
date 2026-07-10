<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Response;

final readonly class TenantSignupUserResponse
{
    public function __construct(
        private string $email,
        private string $status,
        private bool $activationPending,
    ) {
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'status' => $this->status,
            'activationPending' => $this->activationPending,
        ];
    }
}
