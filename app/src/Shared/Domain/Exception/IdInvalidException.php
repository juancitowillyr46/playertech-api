<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

final class IdInvalidException extends \DomainException
{
    public function __construct()
    {
        parent::__construct(
            'id not valid'
        );
    }
}