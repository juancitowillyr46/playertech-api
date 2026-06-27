<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract readonly class AbstractAge
{
    protected function __construct(
        protected int $value
    ) {
        if ($value < 0 || $value > 100) {
            throw new \InvalidArgumentException(
                'Age must be between 0 and 100.'
            );
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}