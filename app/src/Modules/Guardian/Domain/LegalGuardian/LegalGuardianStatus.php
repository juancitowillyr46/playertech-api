<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Domain\LegalGuardian;

final readonly class LegalGuardianStatus
{
    public const ACTIVE = 'ACTIVE';
    public const INACTIVE = 'INACTIVE';

    public function __construct(
        private string $value
    ) {
        if (!in_array($value, [self::ACTIVE, self::INACTIVE], true)) {
            throw new \InvalidArgumentException(sprintf('Invalid legal guardian status: %s', $value));
        }
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function inactive(): self
    {
        return new self(self::INACTIVE);
    }

    public function isActive(): bool
    {
        return self::ACTIVE === $this->value;
    }

    public function isInactive(): bool
    {
        return self::INACTIVE === $this->value;
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
