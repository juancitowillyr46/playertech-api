<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Command;

use App\Modules\Category\Application\Dto\CreateCategoryInput;

final readonly class CreateCategoryCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public CreateCategoryInput $input
    ) {
    }
}