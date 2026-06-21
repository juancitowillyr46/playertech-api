<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final readonly class PhoneNumber
{
    public function __construct(
        private ?string $value
    ) {
        if ($value === null) {
            return;
        }

        if (mb_strlen($value) > 30) {
            throw new \InvalidArgumentException('Phone number too long.');
        }
    }

    public function value(): ?string
    {
        return $this->value;
    }
}