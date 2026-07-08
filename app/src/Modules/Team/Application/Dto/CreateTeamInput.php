<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Dto;

final readonly class CreateTeamInput
{
    public function __construct(
        public ?string $categoryId,

        public ?string $name,
    ) {
    }
}
