<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Storage;

use App\Shared\Domain\Contracts\FileStorage;
use App\Shared\Domain\ValueObject\Media;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

final readonly class LocalStorage implements FileStorage
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
        private string $assetsBaseUrl,
    ) {
    }

    public function upload(UploadedFile $file, string $path): Media
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $fullPath = $this->targetDirectory . '/' . $path;

        try {
            $file->move($fullPath, $fileName);
        } catch (FileException $e) {
            throw new \RuntimeException('Could not save uploaded file', 0, $e);
        }

        $filePath = $path . '/' . $fileName;
        $fileRealPath = $fullPath . '/' . $fileName;

        return new Media(
            $filePath,
            $this->assetsBaseUrl . '/' . $filePath,
            $file->getClientMimeType(),
            $file->getSize(),
            'sha256:' . hash_file('sha256', $fileRealPath)
        );
    }

    public function delete(?Media $media): void
    {
        if ($media === null) {
            return;
        }

        $filePath = $this->targetDirectory . '/' . $media->path();

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
