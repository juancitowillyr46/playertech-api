<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contracts;

use App\Shared\Domain\ValueObject\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileStorage
{
    public function upload(UploadedFile $file, string $path): Media;

    public function delete(?Media $media): void;
}
