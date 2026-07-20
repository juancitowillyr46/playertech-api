<?php

declare(strict_types=1);

namespace App\Modules\Venue\Presentation\Http\Request;

use App\Modules\Venue\Application\Dto\CreateVenueInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateVenueRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "name" es obligatorio.')]
        #[Assert\Length(max: 150)]
        public ?string $name,

        #[Assert\Length(max: 255)]
        public ?string $address = null,

        #[Assert\Length(max: 100)]
        public ?string $city = null,

        #[Assert\Length(max: 80)]
        public ?string $country = null,

        #[Assert\Length(max: 80)]
        public ?string $department = null,

        #[Assert\Length(max: 50)]
        public ?string $phone = null,

        public ?string $notes = null,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['name'] ?? null),
            self::stringOrNull($payload['address'] ?? null),
            self::stringOrNull($payload['city'] ?? null),
            self::stringOrNull($payload['country'] ?? null),
            self::stringOrNull($payload['department'] ?? null),
            self::stringOrNull($payload['phone'] ?? null),
            self::stringOrNull($payload['notes'] ?? null),
        );
    }

    public function toInput(): CreateVenueInput
    {
        return new CreateVenueInput(
            $this->name,
            $this->address,
            $this->city,
            $this->country,
            $this->department,
            $this->phone,
            $this->notes,
        );
    }

    private static function stringOrNull(mixed $value): ?string
    {
        if (null === $value) {
            return null;
        }

        $value = trim((string) $value);

        return '' === $value ? null : $value;
    }
}
