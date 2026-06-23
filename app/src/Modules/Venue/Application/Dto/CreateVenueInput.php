<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateVenueInput
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "name" es obligatorio.')]
        #[Assert\Length(max: 150)]
        public ?string $name,

        #[Assert\Length(max: 255)]
        public ?string $address = null,

        #[Assert\Length(max: 100)]
        public ?string $city = null,

        #[Assert\Length(max: 50)]
        public ?string $phone = null,

        public ?string $notes = null,
    ) {}

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['name'] ?? null),
            self::stringOrNull($payload['address'] ?? null),
            self::stringOrNull($payload['city'] ?? null),
            self::stringOrNull($payload['phone'] ?? null),
            self::stringOrNull($payload['notes'] ?? null),
        );
    }

    private static function stringOrNull(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}