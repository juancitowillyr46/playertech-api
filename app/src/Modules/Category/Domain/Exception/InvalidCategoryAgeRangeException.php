<?php

declare(strict_types=1);

namespace App\Modules\Category\Domain\Exception;

final class InvalidCategoryAgeRangeException extends \DomainException
{
    public function __construct()
    {
        parent::__construct(
            'Minimum age must be lower than maximum age.'
        );
    }
}