<?php

declare(strict_types=1);

namespace App\Shared\Application\Response;

final readonly class MediaResponse
{
    private function __construct(
        private string $path,
        private ?string $url,
        private ?string $mimeType,
        private ?int $size,
        private ?string $checksum,
    ) {
    }

    public static function fromPath(string $path): self
    {
        return new self($path, $path, null, null, null);
    }

    public static function fromDetails(
        string $path,
        ?string $url = null,
        ?string $mimeType = null,
        ?int $size = null,
        ?string $checksum = null
    ): self {
        return new self(
            $path,
            $url ?? $path,
            $mimeType,
            $size,
            $checksum
        );
    }

    public function toArray(): array
    {
        return [
            'path' => $this->path,
            'url' => $this->url,
            'mimeType' => $this->mimeType,
            'size' => $this->size,
            'checksum' => $this->checksum,
        ];
    }
}
