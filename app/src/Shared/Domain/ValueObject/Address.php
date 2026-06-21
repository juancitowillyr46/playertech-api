<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final readonly class Address
{
    public function __construct(
        private ?string $value
    ) {
        if ($value !== null && mb_strlen($value) > 255) {
            throw new \InvalidArgumentException('Address too long.');
        }
    }

    public function value(): ?string
    {
        return $this->value;
    }
}