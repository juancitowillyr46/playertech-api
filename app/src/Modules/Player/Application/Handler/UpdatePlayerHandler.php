<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryStatus;
use App\Modules\Player\Application\Command\UpdatePlayerCommand;
use App\Modules\Player\Application\Response\PlayerResponse;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Modules\Player\Domain\Exception\PlayerAlreadyExistsException;

final readonly class UpdatePlayerHandler
{
    public function __construct(
        private PlayerFinder $playerFinder,
        private CategoryFinder $categoryFinder,
        private PlayerRepository $playerRepository,
    ) {
    }

    public function __invoke(UpdatePlayerCommand $command): PlayerResponse
    {
        $academyId = new AcademyId($command->academyId);
        $playerId = new PlayerId($command->playerId);
        $player = $this->playerFinder->findOrFail($academyId, $playerId);

        $duplicate = $this->playerRepository->findOneByDocumentNumber($academyId, $command->input->documentNumber);
        if (null !== $duplicate && $duplicate->id()->value() !== $player->id()->value()) {
            throw new PlayerAlreadyExistsException();
        }

        $categoryId = null;
        if (null !== $command->input->categoryId) {
            $categoryId = new CategoryId($command->input->categoryId);
            $category = $this->categoryFinder->findOrFail($academyId, $categoryId);

            if (!$category->status()->isActive()) {
                throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('La categoría debe estar activa.');
            }
        }

        $player->updateProfile(
            $command->input->documentType,
            $command->input->firstName,
            $command->input->lastName,
            new \DateTimeImmutable($command->input->birthDate),
            $command->input->documentNumber,
            $command->input->email,
            $command->input->phone,
            $command->input->nationality,
            $command->input->gender,
            $command->input->federationId,
            $command->input->dominantFoot,
            $command->actorId,
        );

        $player->updateCategory($categoryId, $command->actorId);

        $this->playerRepository->save($player);

        return PlayerResponse::fromPlayer($player);
    }
}
