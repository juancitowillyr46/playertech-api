<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Player\ListByGuardian;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Guardian\Domain\Exception\LegalGuardianNotFoundException;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Modules\Player\Domain\Exception\PlayerNotFoundException;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianRepository;
use App\Shared\Domain\Relationship\Relationship;

final readonly class ListGuardianPlayersHandler
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private LegalGuardianRepository $guardianRepository,
        private PlayerGuardianRepository $playerGuardianRepository,
        private CategoryRepository $categoryRepository,
    ) {
    }

    /**
     * @return GuardianPlayerListItemResponse[]
     */
    public function __invoke(ListGuardianPlayersQuery $query): array
    {
        $guardian = $this->guardianRepository->findById($query->academyId, $query->guardianId);

        if (null === $guardian) {
            throw new LegalGuardianNotFoundException();
        }

        $relations = $this->playerGuardianRepository->findAllByGuardian($query->academyId, $query->guardianId);

        return array_values(array_map(function ($relation) use ($query, $guardian): GuardianPlayerListItemResponse {
            $player = $this->playerRepository->findById($query->academyId, $relation->playerId());

            if (null === $player) {
                throw new PlayerNotFoundException();
            }

            $categoryName = null;
            if (null !== $player->categoryId()) {
                $category = $this->categoryRepository->findById($query->academyId, $player->categoryId());
                $categoryName = $category?->name()->value();
            }

            return new GuardianPlayerListItemResponse(
                $player->firstName(),
                $player->lastName(),
                $categoryName,
                Relationship::tryFrom($guardian->relationship())?->label() ?? $guardian->relationship(),
                $relation->isPrimary(),
            );
        }, $relations));
    }
}
