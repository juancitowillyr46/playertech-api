<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Photo\Upload;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Response\PlayerResponse;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Shared\Domain\Contracts\FileStorage;
use App\Shared\Domain\Exception\InvalidMimeTypeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UploadPlayerPhotoHandler
{
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
    ];

    public function __construct(
        private PlayerFinder $playerFinder,
        private PlayerRepository $playerRepository,
        private FileStorage $fileStorage,
    ) {
    }

    public function __invoke(UploadPlayerPhotoCommand $command): PlayerResponse
    {
        $player = $this->playerFinder->findOrFail(
            new AcademyId($command->academyId),
            new PlayerId($command->playerId)
        );

        $this->assertAllowedMimeType($command->file);

        $this->fileStorage->delete($player->photo());

        $media = $this->fileStorage->upload(
            $command->file,
            'images/players/' . $player->academyId()->value() . '/' . $player->id()->value()
        );

        $player->updatePhoto($media, $command->actorId);

        $this->playerRepository->save($player);

        return PlayerResponse::fromPlayer($player);
    }

    private function assertAllowedMimeType(UploadedFile $file): void
    {
        $mimeType = $file->getMimeType() ?? $file->getClientMimeType() ?? '';

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            throw new InvalidMimeTypeException($mimeType, self::ALLOWED_MIME_TYPES);
        }
    }
}
