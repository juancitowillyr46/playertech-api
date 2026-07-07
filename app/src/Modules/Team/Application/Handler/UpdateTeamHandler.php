<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Team\Application\Command\UpdateTeamCommand;
use App\Modules\Team\Application\Response\TeamResponse;
use App\Modules\Team\Application\Services\TeamFinder;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Shared\Domain\ValueObject\Name;

final readonly class UpdateTeamHandler
{
    public function __construct(
        private TeamRepository $teamRepository,
        private TeamFinder $teamFinder,
        private CategoryFinder $categoryFinder,
    ) {
    }

    public function __invoke(UpdateTeamCommand $command): TeamResponse
    {
        $academyId = new AcademyId($command->academyId);
        $team = $this->teamFinder->findOrFail($academyId, new TeamId($command->teamId));
        $categoryId = new CategoryId($command->input->categoryId);

        $this->categoryFinder->findOrFail($academyId, $categoryId);

        $team->update(
            $categoryId,
            new Name($command->input->name),
            $command->actorId
        );

        $this->teamRepository->save($team);

        return TeamResponse::fromTeam($team);
    }
}
