<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final readonly class Name
{
    public function __construct(
        private string $value
    ) {
        $value = trim($value);

        if ($value === '') {
            throw new \InvalidArgumentException('Name cannot be empty.');
        }

        if (mb_strlen($value) > 150) {
            throw new \InvalidArgumentException('Name is too long.');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}