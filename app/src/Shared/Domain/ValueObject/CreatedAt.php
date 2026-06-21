<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final readonly class CreatedAt
{
    public function __construct(
        private \DateTimeImmutable $value
    ) {
    }

    public static function now(): self
    {
        return new self(new \DateTimeImmutable());
    }

    public function value(): \DateTimeImmutable
    {
        return $this->value;
    }
}