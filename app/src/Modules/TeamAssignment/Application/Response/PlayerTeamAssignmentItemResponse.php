<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Application\Response;

final readonly class PlayerTeamAssignmentItemResponse
{
    public function __construct(
        public string $id,
        public string $playerId,
        public string $teamId,
        public string $startDate,
        public ?string $endDate,
        public bool $isPrimary,
        public array $team,
    ) {
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
            'team' => $this->team,
        ];
    }
}
