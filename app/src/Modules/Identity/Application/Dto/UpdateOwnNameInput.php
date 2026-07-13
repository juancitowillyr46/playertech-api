<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Dto;

final readonly class UpdateOwnNameInput
{
    public function __construct(
        public ?string $fullName,
    ) {
    }
}
