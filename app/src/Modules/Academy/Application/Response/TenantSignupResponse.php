<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Response;

use App\Modules\Team\Application\Response\TeamResponse;

final readonly class TenantSignupResponse
{
    public function __construct(
        private AcademyResponse $academy,
        private TenantSignupUserResponse $user,
        private ?TeamResponse $team = null,
    ) {
    }

    public function toArray(): array
    {
        $payload = [
            'academy' => $this->academy->toArray(),
            'user' => $this->user->toArray(),
        ];

        if (null !== $this->team) {
            $payload['team'] = $this->team->toArray();
        }

        return $payload;
    }
}
