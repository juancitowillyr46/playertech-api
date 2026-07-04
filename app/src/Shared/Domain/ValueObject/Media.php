<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\InvalidMimeTypeException;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

final readonly class Media implements JsonSerializable
{
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
    ];

    public function __construct(
        private string $path,
        private string $url,
        private string $mimeType,
        private int $size,
        private string $checksum
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if (!in_array($this->mimeType, self::ALLOWED_MIME_TYPES, true)) {
            throw new InvalidMimeTypeException($this->mimeType, self::ALLOWED_MIME_TYPES);
        }

        if ($this->size <= 0) {
            throw new InvalidArgumentException('Size must be a positive integer.');
        }

        if (!preg_match('/^sha256:[a-f0-9]{64}$/', $this->checksum)) {
            throw new InvalidArgumentException('Invalid checksum format. Expected sha256:<hash>.');
        }
    }

    public function path(): string
    {
        return $this->path;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function mimeType(): string
    {
        return $this->mimeType;
    }

    public function size(): int
    {
        return $this->size;
    }

    public function checksum(): string
    {
        return $this->checksum;
    }

    #[ArrayShape([
        'path' => "string",
        'url' => "string",
        'mime_type' => "string",
        'size' => "int",
        'checksum' => "string"
    ])]
    public function jsonSerialize(): array
    {
        return [
            'path' => $this->path,
            'url' => $this->url,
            'mime_type' => $this->mimeType,
            'size' => $this->size,
            'checksum' => $this->checksum,
        ];
    }
}
