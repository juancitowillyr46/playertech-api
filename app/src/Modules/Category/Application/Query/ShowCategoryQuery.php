<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Query;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\CategoryId;

final readonly class ShowCategoryQuery
{
    public function __construct(
        public AcademyId $academyId,
        public CategoryId $categoryId,
    ) {
    }
}