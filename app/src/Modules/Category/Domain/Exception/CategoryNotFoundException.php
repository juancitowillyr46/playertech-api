<?php

declare(strict_types=1);

namespace App\Modules\Category\Domain\Exception;

final class CategoryNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct(
            'category not found'
        );
    }
}