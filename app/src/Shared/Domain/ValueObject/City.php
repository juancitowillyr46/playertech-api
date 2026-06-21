<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final readonly class City
{
    public function __construct(
        private ?string $value
    ) {
        if ($value !== null && mb_strlen($value) > 120) {
            throw new \InvalidArgumentException('City too long.');
        }
    }

    public function value(): ?string
    {
        return $this->value;
    }
}