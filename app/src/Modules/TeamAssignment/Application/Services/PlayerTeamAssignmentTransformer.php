<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Application\Services;

use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Modules\TeamAssignment\Application\Response\PlayerTeamAssignmentItemResponse;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignment;

final readonly class PlayerTeamAssignmentTransformer
{
    public function __construct(
        private TeamRepository $teamRepository,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function transform(TeamAssignment $assignment): PlayerTeamAssignmentItemResponse
    {
        $team = $this->teamRepository->findById($assignment->academyId(), $assignment->teamId());
        $category = null;

        if (null !== $team) {
            $category = $this->categoryRepository->findById($assignment->academyId(), $team->categoryId());
        }

        return new PlayerTeamAssignmentItemResponse(
            $assignment->id()->value(),
            $assignment->playerId()->value(),
            $assignment->teamId()->value(),
            $assignment->startDate()->format('Y-m-d'),
            $assignment->endDate()?->format('Y-m-d'),
            $assignment->isPrimary(),
            [
                'id' => null !== $team ? $team->id()->value() : $assignment->teamId()->value(),
                'name' => null !== $team ? $team->name()->value() : null,
                'categoryId' => null !== $team ? $team->categoryId()->value() : null,
                'categoryName' => null !== $category ? $category->name()->value() : null,
            ]
        );
    }
}
