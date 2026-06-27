<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Command;

final readonly class ActivateCategoryCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public string $categoryId,
    ) {
    }
}