<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final readonly class LogoPath
{
    public function __construct(
        private ?string $value
    ) {
        if ($value !== null && mb_strlen($value) > 255) {
            throw new \InvalidArgumentException('Logo path too long.');
        }
    }

    public function value(): ?string
    {
        return $this->value;
    }
}