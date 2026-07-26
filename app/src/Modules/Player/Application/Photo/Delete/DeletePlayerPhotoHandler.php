<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Photo\Delete;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Shared\Domain\Contracts\FileStorage;

final readonly class DeletePlayerPhotoHandler
{
    public function __construct(
        private PlayerFinder $playerFinder,
        private PlayerRepository $playerRepository,
        private FileStorage $fileStorage,
    ) {
    }

    public function __invoke(DeletePlayerPhotoCommand $command): void
    {
        $player = $this->playerFinder->findOrFail(
            new AcademyId($command->academyId),
            new PlayerId($command->playerId)
        );

        if ($this->isInitializedMedia($player->photo())) {
            $this->fileStorage->delete($player->photo());
        }

        $player->updatePhoto(null, $command->actorId);
        $this->playerRepository->save($player);
    }

    private function isInitializedMedia(?\App\Shared\Domain\ValueObject\Media $media): bool
    {
        if (null === $media) {
            return false;
        }

        $reflection = new \ReflectionObject($media);

        foreach (['path', 'url', 'mimeType', 'size', 'checksum'] as $propertyName) {
            $property = $reflection->getProperty($propertyName);

            if (!$property->isInitialized($media)) {
                return false;
            }
        }

        return true;
    }
}
