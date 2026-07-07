<?php

declare(strict_types=1);

namespace App\Modules\Category\Domain\Exception;

use App\Shared\Domain\Exception\ConflictException;

final class CategoryInactiveException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('La categoría se encuentra inactiva.');
    }
}
