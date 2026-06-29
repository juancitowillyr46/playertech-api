<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Command;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class ImportPlayersCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public UploadedFile $file,
    ) {
    }
}
