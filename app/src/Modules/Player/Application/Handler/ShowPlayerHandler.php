<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Handler;

use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Player\Application\Query\ShowPlayerQuery;
use App\Modules\Player\Application\Response\PlayerResponse;
use App\Modules\Player\Application\Services\PlayerFinder;

final readonly class ShowPlayerHandler
{
    public function __construct(
        private PlayerFinder $playerFinder,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function __invoke(ShowPlayerQuery $query): PlayerResponse
    {
        $player = $this->playerFinder->findOrFail($query->academyId, $query->playerId);
        $categoryName = null;

        if (null !== $player->categoryId()) {
            $category = $this->categoryRepository->findById($query->academyId, new CategoryId($player->categoryId()->value()));
            $categoryName = null === $category ? null : $category->name()->value();
        }

        return PlayerResponse::fromPlayer($player, $categoryName);
    }
}
