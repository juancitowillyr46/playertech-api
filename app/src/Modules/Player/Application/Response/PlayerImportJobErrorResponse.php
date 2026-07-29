<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Response;

final readonly class PlayerImportJobErrorResponse
{
    public function __construct(
        private ?int $row,
        private string $field,
        private string $message,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['row']) ? (int) $data['row'] : null,
            (string) ($data['field'] ?? 'unknown'),
            (string) ($data['message'] ?? ''),
        );
    }

    public function toArray(): array
    {
        return [
            'row' => $this->row,
            'field' => $this->field,
            'message' => $this->message,
        ];
    }
}
