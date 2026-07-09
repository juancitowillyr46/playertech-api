<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Domain\TeamAssignment;

use Symfony\Component\Uid\Uuid;

final readonly class TeamAssignmentId
{
    public function __construct(private string $value)
    {
        if (!Uuid::isValid($value)) {
            throw new \InvalidArgumentException('Invalid team assignment id.');
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
}
