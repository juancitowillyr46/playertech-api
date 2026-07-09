<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\TeamAssignment\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignment;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentId;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentRepository;

final class InMemoryTeamAssignmentRepository implements TeamAssignmentRepository
{
    /** @var array<string, TeamAssignment> */
    public array $items = [];

    public function save(TeamAssignment $assignment): void
    {
        $this->items[$assignment->id()->value()] = $assignment;
    }

    public function findById(AcademyId $academyId, TeamAssignmentId $assignmentId): ?TeamAssignment
    {
        foreach ($this->items as $item) {
            if ($item->academyId()->value() === $academyId->value()
                && $item->id()->value() === $assignmentId->value()
                && null === $item->deletedAt()) {
                return $item;
            }
        }

        return null;
    }

    public function findByPlayerAndTeam(AcademyId $academyId, PlayerId $playerId, TeamId $teamId): ?TeamAssignment
    {
        foreach ($this->items as $item) {
            if ($item->academyId()->value() === $academyId->value()
                && $item->playerId()->value() === $playerId->value()
                && $item->teamId()->value() === $teamId->value()
                && null === $item->deletedAt()) {
                return $item;
            }
        }

        return null;
    }

    public function findAllByPlayer(AcademyId $academyId, PlayerId $playerId): array
    {
        return array_values(array_filter(
            $this->items,
            static fn (TeamAssignment $assignment): bool => $assignment->academyId()->value() === $academyId->value()
                && $assignment->playerId()->value() === $playerId->value()
                && null === $assignment->deletedAt()
        ));
    }

    public function findPrimaryByPlayer(AcademyId $academyId, PlayerId $playerId): ?TeamAssignment
    {
        foreach ($this->items as $item) {
            if ($item->academyId()->value() === $academyId->value()
                && $item->playerId()->value() === $playerId->value()
                && $item->isPrimary()
                && $item->isActive()
                && null === $item->deletedAt()) {
                return $item;
            }
        }

        return null;
    }

    public function findActiveByPlayerExcept(AcademyId $academyId, PlayerId $playerId, ?TeamAssignmentId $excludedAssignmentId = null): ?TeamAssignment
    {
        foreach ($this->items as $item) {
            if ($item->academyId()->value() === $academyId->value()
                && $item->playerId()->value() === $playerId->value()
                && $item->isActive()
                && null === $item->deletedAt()
                && (null === $excludedAssignmentId || $item->id()->value() !== $excludedAssignmentId->value())) {
                return $item;
            }
        }

        return null;
    }
}
