<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Document;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PlayerDocumentStorage
{
    public function store(UploadedFile $file, string $extension): string;
    public function path(string $storageName): string;
    public function delete(string $storageName): void;
}
