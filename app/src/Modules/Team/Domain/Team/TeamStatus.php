<?php

declare(strict_types=1);

namespace App\Modules\Team\Domain\Team;

final readonly class TeamStatus
{
    public const ACTIVE = 'ACTIVE';
    public const INACTIVE = 'INACTIVE';

    public function __construct(
        private string $value
    ) {
        if (!in_array($value, [self::ACTIVE, self::INACTIVE], true)) {
            throw new \InvalidArgumentException(sprintf('Invalid team status: %s', $value));
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
