<?php

declare(strict_types=1);

namespace App\Modules\Player\Infrastructure\Persistence;

use App\Modules\Player\Domain\Document\PlayerDocumentStorage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class FileSystemPlayerDocumentStorage implements PlayerDocumentStorage
{
    public function __construct(private string $directory) {}

    public function store(UploadedFile $file, string $extension): string
    {
        if (!is_dir($this->directory) && !mkdir($this->directory, 0775, true) && !is_dir($this->directory)) {
            throw new \RuntimeException('No fue posible crear el almacenamiento documental.');
        }
        $storageName = bin2hex(random_bytes(16)) . '.' . $extension;
        $file->move($this->directory, $storageName);
        return $storageName;
    }

    public function path(string $storageName): string
    {
        if ($storageName !== basename($storageName)) {
            throw new \RuntimeException('Nombre de almacenamiento inválido.');
        }
        $path = $this->directory . '/' . $storageName;
        if (!is_file($path)) {
            throw new \RuntimeException('El archivo documental no existe.');
        }
        return $path;
    }

    public function delete(string $storageName): void
    {
        $path = $this->directory . '/' . basename($storageName);
        if (is_file($path)) {
            unlink($path);
        }
    }
}
