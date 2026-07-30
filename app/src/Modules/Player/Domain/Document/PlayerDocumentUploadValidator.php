<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Document;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PlayerDocumentUploadValidator
{
    /** @return array{originalFileName: string, mimeType: string, extension: string, size: int} */
    public function validate(UploadedFile $file): array;
}
