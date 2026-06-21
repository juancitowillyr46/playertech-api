<?php

declare(strict_types=1);

namespace App\Modules\Academy\Domain\Academy;

final readonly class AcademyStatus
{
    public const ACTIVE = 'ACTIVE';
    public const SUSPENDED = 'SUSPENDED';

    public function __construct(
        private string $value
    ) {
        if (!in_array(
            $value,
            [
                self::ACTIVE,
                self::SUSPENDED,
            ],
            true
        )) {
            throw new \InvalidArgumentException(
                sprintf('Invalid academy status: %s', $value)
            );
        }
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function suspended(): self
    {
        return new self(self::SUSPENDED);
    }

    public function isActive(): bool
    {
        return self::ACTIVE === $this->value;
    }

    public function isSuspended(): bool
    {
        return self::SUSPENDED === $this->value;
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