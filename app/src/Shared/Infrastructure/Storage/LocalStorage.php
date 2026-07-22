<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Storage;

use App\Shared\Domain\Contracts\FileStorage;
use App\Shared\Domain\Exception\FileTooLargeException;
use App\Shared\Domain\Exception\InvalidMimeTypeException;
use App\Shared\Domain\ValueObject\Media;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

final readonly class LocalStorage implements FileStorage
{
    private const MAX_FILE_SIZE = 2_097_152;

    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/svg+xml',
    ];

    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
        private string $assetsBaseUrl,
    ) {
    }

    public function upload(UploadedFile $file, string $path): Media
    {
        $mimeType = $file->getMimeType() ?? $file->getClientMimeType() ?? '';

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            throw new InvalidMimeTypeException($mimeType, self::ALLOWED_MIME_TYPES);
        }

        $size = $file->getSize();
        if (false === $size || $size <= 0 || $size > self::MAX_FILE_SIZE) {
            throw new FileTooLargeException(self::MAX_FILE_SIZE);
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $extension = $file->guessExtension() ?? $this->extensionForMimeType($mimeType);
        $fileName = $safeFilename . '-' . uniqid('', true) . '.' . $extension;

        $fullPath = $this->targetDirectory . '/' . $path;

        if (!is_dir($fullPath) && !mkdir($fullPath, 0775, true) && !is_dir($fullPath)) {
            throw new \RuntimeException('Could not create upload directory.');
        }

        if (!is_dir($fullPath) || !is_writable($fullPath)) {
            throw new \RuntimeException(sprintf('Upload directory is not writable: %s', $fullPath));
        }

        try {
            $file->move($fullPath, $fileName);
        } catch (FileException $e) {
            throw new \RuntimeException('Could not save uploaded file.', 0, $e);
        }

        $filePath = $path . '/' . $fileName;
        $fileRealPath = $fullPath . '/' . $fileName;
        $storedMimeType = mime_content_type($fileRealPath) ?: $mimeType;
        $storedSize = filesize($fileRealPath);

        if (false === $storedSize || $storedSize <= 0) {
            throw new \RuntimeException('Could not resolve uploaded file size.');
        }

        return new Media(
            $filePath,
            $this->assetsBaseUrl . '/' . $filePath,
            $storedMimeType,
            (int) $storedSize,
            'sha256:' . hash_file('sha256', $fileRealPath)
        );
    }

    public function delete(?Media $media): void
    {
        if (null === $media) {
            return;
        }

        $filePath = $this->targetDirectory . '/' . $media->path();

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }

    private function extensionForMimeType(string $mimeType): string
    {
        return match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/svg+xml' => 'svg',
            default => 'bin',
        };
    }
}
