<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Services;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Team\Domain\Exception\TeamNotFoundException;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Shared\Domain\Exception\IdInvalidException;
use Symfony\Component\Uid\Uuid;

final readonly class TeamFinder
{
    public function __construct(
        private TeamRepository $teamRepository
    ) {
    }

    public function findOrFail(
        AcademyId $academyId,
        TeamId $teamId
    ): Team {
        if (!Uuid::isValid($academyId->value()) || !Uuid::isValid($teamId->value())) {
            throw new IdInvalidException();
        }

        $team = $this->teamRepository->findById($academyId, $teamId);

        if (null === $team) {
            throw new TeamNotFoundException();
        }

        return $team;
    }
}
