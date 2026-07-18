<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Response;

final readonly class TenantActivationResponse
{
    public function __construct(
        private string $email,
        private string $status,
        private bool $activated,
        private bool $alreadyActivated = false,
    ) {
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'status' => $this->status,
            'activated' => $this->activated,
            'alreadyActivated' => $this->alreadyActivated,
        ];
    }
}
