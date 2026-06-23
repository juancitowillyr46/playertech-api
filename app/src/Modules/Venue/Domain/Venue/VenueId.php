<?php

declare(strict_types=1);

namespace App\Modules\Venue\Domain\Venue;

use Symfony\Component\Uid\Uuid;

final readonly class VenueId
{
    public function __construct(
        private string $value
    ) {
        if (!Uuid::isValid($value)) {
            throw new \InvalidArgumentException('Invalid venue id.');
        }
    }

    public static function generate(): self
    {
        return new self(Uuid::v7()->toRfc4122());
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