<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Handler;

use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Modules\Player\Application\Query\ShowPlayerQuery;
use App\Modules\Player\Application\Response\PlayerResponse;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianRepository;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentRepository;

final readonly class ShowPlayerHandler
{
    public function __construct(
        private PlayerFinder $playerFinder,
        private CategoryRepository $categoryRepository,
        private PlayerGuardianRepository $playerGuardianRepository,
        private LegalGuardianRepository $guardianRepository,
        private TeamAssignmentRepository $teamAssignmentRepository,
        private TeamRepository $teamRepository,
    ) {
    }

    public function __invoke(ShowPlayerQuery $query): PlayerResponse
    {
        $player = $this->playerFinder->findOrFail($query->academyId, $query->playerId);
        $categoryName = null;
        $legalGuardianMain = null;
        $teamMain = null;

        if (null !== $player->categoryId()) {
            $category = $this->categoryRepository->findById($query->academyId, new CategoryId($player->categoryId()->value()));
            $categoryName = null === $category ? null : $category->name()->value();
        }

        $primaryGuardian = $this->playerGuardianRepository->findPrimaryByPlayer($query->academyId, $query->playerId);
        if (null !== $primaryGuardian) {
            $guardian = $this->guardianRepository->findById($query->academyId, $primaryGuardian->guardianId());

            if (null !== $guardian) {
                $legalGuardianMain = [
                    'firstName' => $guardian->firstName(),
                    'lastName' => $guardian->lastName(),
                ];
            }
        }

        $primaryAssignment = $this->teamAssignmentRepository->findPrimaryByPlayer($query->academyId, $query->playerId);
        if (null !== $primaryAssignment) {
            $assignedTeam = $this->teamRepository->findById($query->academyId, $primaryAssignment->teamId());

            if (null !== $assignedTeam) {
                $teamMain = [
                    'name' => $assignedTeam->name()->value(),
                ];
            }
        }

        return PlayerResponse::fromPlayer($player, $categoryName, $legalGuardianMain, $teamMain);
    }
}
