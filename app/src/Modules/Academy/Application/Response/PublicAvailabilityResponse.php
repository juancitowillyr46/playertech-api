<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Response;

final readonly class PublicAvailabilityResponse
{
    public function __construct(
        private bool $contactEmailAvailable,
        private bool $phoneAvailable,
    ) {
    }

    public function toArray(): array
    {
        return [
            'contactEmailAvailable' => $this->contactEmailAvailable,
            'phoneAvailable' => $this->phoneAvailable,
        ];
    }
}
