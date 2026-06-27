<?php

declare(strict_types=1);

namespace App\Modules\Category\Domain\Exception;

final class CategoryAlreadyExistsException extends \DomainException
{
    public function __construct()
    {
        parent::__construct(
            'A category with the same name already exists for this academy.'
        );
    }
}