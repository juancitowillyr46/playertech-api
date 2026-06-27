<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final readonly class MinimumAge extends AbstractAge
{
    public function __construct(int $value)
    {
        parent::__construct($value);
    }
}