<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Command;

use App\Modules\Category\Application\Dto\UpdateCategoryInput;

final readonly class UpdateCategoryCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public string $categoryId,
        public UpdateCategoryInput $input,
    ) {
    }
}
