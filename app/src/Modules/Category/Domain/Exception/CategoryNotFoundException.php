<?php

declare(strict_types=1);

namespace App\Modules\Category\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;

final class CategoryNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct(
            'category not found'
        );
    }
}
