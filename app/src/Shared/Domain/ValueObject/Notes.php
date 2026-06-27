<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final readonly class Notes
{
    private string $value;
    public function __construct(string $value) {

        $value = trim($value);

        if ($value === '') {
            throw new \InvalidArgumentException('Notes cannot be empty.');
        }

        if (mb_strlen($value) > 150) {
            throw new \InvalidArgumentException('Notes cannot exceed 150 characters.');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}