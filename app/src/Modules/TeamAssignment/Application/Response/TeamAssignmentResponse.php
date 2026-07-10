<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Application\Response;

use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignment;

final readonly class TeamAssignmentResponse
{
    public function __construct(
        public string $id,
        public string $playerId,
        public string $teamId,
        public string $startDate,
        public ?string $endDate,
        public bool $isPrimary
    ) {
    }

    public static function fromEntity(TeamAssignment $assignment): self
    {
        return new self(
            $assignment->id()->value(),
            $assignment->playerId()->value(),
            $assignment->teamId()->value(),
            $assignment->startDate()->format('Y-m-d'),
            $assignment->endDate()?->format('Y-m-d'),
            $assignment->isPrimary()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'playerId' => $this->playerId,
            'teamId' => $this->teamId,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'isPrimary' => $this->isPrimary,
        ];
    }
}
