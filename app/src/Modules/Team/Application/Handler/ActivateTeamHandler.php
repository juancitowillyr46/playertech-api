<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Team\Application\Command\ActivateTeamCommand;
use App\Modules\Team\Application\Services\TeamFinder;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository;

final readonly class ActivateTeamHandler
{
    public function __construct(
        private TeamRepository $teamRepository,
        private TeamFinder $teamFinder,
    ) {
    }

    public function __invoke(ActivateTeamCommand $command): void
    {
        $academyId = new AcademyId($command->academyId);
        $team = $this->teamFinder->findOrFail($academyId, new TeamId($command->teamId));

        $team->activate($command->actorId);

        $this->teamRepository->save($team);
    }
}
