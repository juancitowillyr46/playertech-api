<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final readonly class UpdatedAt
{
    public function __construct(
        private ?\DateTimeImmutable $value
    ) {
    }

    public function value(): ?\DateTimeImmutable
    {
        return $this->value;
    }
}