<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Shield\Upload;

use App\Shared\Application\Command\Command;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UploadAcademyShieldCommand extends Command
{
    public function __construct(
        string $academyId,
        private readonly UploadedFile $file,
        string $userId,
    ) {
        parent::__construct($academyId, $userId);
    }

    public function getFile(): UploadedFile
    {
        return $this->file;
    }
}
