<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Response;

final readonly class TenantSignupResponse
{
    public function __construct(
        private AcademyResponse $academy,
        private TenantSignupUserResponse $user,
    ) {
    }

    public function toArray(): array
    {
        return [
            'academy' => $this->academy->toArray(),
            'user' => $this->user->toArray(),
        ];
    }
}
