<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Photo\Upload;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UploadPlayerPhotoCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public string $playerId,
        public UploadedFile $file,
    ) {
    }
}
