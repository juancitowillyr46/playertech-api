<?php

declare(strict_types=1);

namespace App\Modules\Membership\Domain\Membership;

final readonly class MembershipStatus
{
    public const ACTIVE = 'ACTIVE';
    public const SUSPENDED = 'SUSPENDED';
    public const WITHDRAWN = 'WITHDRAWN';

    public function __construct(
        private string $value
    )
    {
        if (!in_array($value, [self::ACTIVE, self::SUSPENDED, self::WITHDRAWN], true)) {
            throw new \InvalidArgumentException(sprintf('Invalid membership status: %s', $value));
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

    public static function withdrawn(): self
    {
        return new self(self::WITHDRAWN);
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
