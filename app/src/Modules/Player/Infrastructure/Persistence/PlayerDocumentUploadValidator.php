<?php

declare(strict_types=1);

namespace App\Modules\Player\Infrastructure\Persistence;

use App\Modules\Player\Domain\Document\PlayerDocumentUploadValidator as Contract;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class PlayerDocumentUploadValidator implements Contract
{
    private const MAX_SIZE = 3145728;
    private const MIME_EXTENSIONS = ['application/pdf' => 'pdf', 'image/jpeg' => 'jpg', 'image/png' => 'png'];

    public function validate(UploadedFile $file): array
    {
        $size = $file->getSize();
        $mime = $file->getMimeType() ?: '';
        if (false === $size || $size <= 0 || $size > self::MAX_SIZE) { throw new \InvalidArgumentException('El archivo debe pesar entre 1 byte y 3 MB.'); }
        if (!isset(self::MIME_EXTENSIONS[$mime])) { throw new \InvalidArgumentException('El formato del archivo no está soportado.'); }
        $extension = self::MIME_EXTENSIONS[$mime];
        $original = pathinfo($file->getClientOriginalName(), PATHINFO_BASENAME);
        $original = preg_replace('/[^A-Za-z0-9._ -]/', '', $original) ?: 'documento.' . $extension;
        $original = trim(str_replace(['..', '/', '\\'], '', $original));
        return ['originalFileName' => $original, 'mimeType' => $mime, 'extension' => $extension, 'size' => (int) $size];
    }
}
