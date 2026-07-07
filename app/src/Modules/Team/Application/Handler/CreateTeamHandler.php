<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Team\Application\Command\CreateTeamCommand;
use App\Modules\Team\Application\Response\TeamResponse;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Name;

final readonly class CreateTeamHandler
{
    public function __construct(
        private TeamRepository $teamRepository,
        private CategoryFinder $categoryFinder,
    ) {
    }

    public function __invoke(CreateTeamCommand $command): TeamResponse
    {
        $academyId = new AcademyId($command->academyId);
        $categoryId = new CategoryId($command->input->categoryId);

        $this->categoryFinder->findOrFail($academyId, $categoryId);

        $team = Team::create(
            TeamId::generate(),
            $academyId,
            $categoryId,
            new Name($command->input->name),
            AuditTrail::create($command->actorId),
        );

        $this->teamRepository->save($team);

        return TeamResponse::fromTeam($team);
    }
}
