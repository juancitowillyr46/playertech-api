<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Player\Options;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Guardian\Domain\Exception\LegalGuardianNotFoundException;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Modules\Player\Domain\Player\PlayerRepository;

final readonly class ListAvailablePlayersHandler
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private LegalGuardianRepository $guardianRepository,
        private CategoryRepository $categoryRepository,
    ) {
    }

    /**
     * @return AvailablePlayerOptionResponse[]
     */
    public function __invoke(ListAvailablePlayersQuery $query): array
    {
        if (null === $this->guardianRepository->findById($query->academyId, $query->guardianId)) {
            throw new LegalGuardianNotFoundException();
        }

        $players = $this->playerRepository->findAvailableByGuardian($query->academyId, $query->guardianId, $query->query);

        return array_values(array_map(function ($player): AvailablePlayerOptionResponse {
            $categoryName = null;

            if (null !== $player->categoryId()) {
                $category = $this->categoryRepository->findById($player->academyId(), $player->categoryId());
                $categoryName = $category?->name()->value();
            }

            return AvailablePlayerOptionResponse::fromPlayer($player, $categoryName);
        }, $players));
    }
}
