<?php

declare(strict_types=1);

namespace App\Modules\Venue\Presentation\Http\Request;

use App\Modules\Venue\Application\Dto\UpdateVenueInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateVenueRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "name" es obligatorio.')]
        #[Assert\Length(max: 150, maxMessage: 'El campo "name" excede la longitud máxima permitida.')]
        public ?string $name,

        #[Assert\Length(max: 255, maxMessage: 'El campo "address" excede la longitud máxima permitida.')]
        public ?string $address = null,

        #[Assert\Length(max: 120, maxMessage: 'El campo "city" excede la longitud máxima permitida.')]
        public ?string $city = null,

        #[Assert\Length(max: 80, maxMessage: 'El campo "country" excede la longitud máxima permitida.')]
        public ?string $country = null,

        #[Assert\Length(max: 80, maxMessage: 'El campo "department" excede la longitud máxima permitida.')]
        public ?string $department = null,

        #[Assert\Length(max: 30, maxMessage: 'El campo "phone" excede la longitud máxima permitida.')]
        public ?string $phone = null,

        #[Assert\Length(max: 255, maxMessage: 'El campo "notes" excede la longitud máxima permitida.')]
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

    public function toInput(): UpdateVenueInput
    {
        return new UpdateVenueInput(
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
