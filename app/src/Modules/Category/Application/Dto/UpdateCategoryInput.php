<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Dto;

final readonly class UpdateCategoryInput
{
    public function __construct(
        public ?string $categoryKey,

        public ?string $name,

        public ?int $minAge,

        public ?int $maxAge,

        public ?string $description = null,
    ) {
    }
}
