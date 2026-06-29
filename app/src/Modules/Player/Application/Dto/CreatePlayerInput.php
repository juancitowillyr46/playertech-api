<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreatePlayerInput
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "first_name" es obligatorio.')]
        #[Assert\Length(max: 100, maxMessage: 'El campo "first_name" excede la longitud máxima permitida.')]
        public ?string $firstName,

        #[Assert\NotBlank(message: 'El campo "last_name" es obligatorio.')]
        #[Assert\Length(max: 100, maxMessage: 'El campo "last_name" excede la longitud máxima permitida.')]
        public ?string $lastName,

        #[Assert\NotBlank(message: 'El campo "birth_date" es obligatorio.')]
        #[Assert\Date(message: 'El campo "birth_date" debe tener un formato de fecha válido.')]
        public ?string $birthDate,

        #[Assert\NotBlank(message: 'El campo "document_number" es obligatorio.')]
        #[Assert\Length(max: 30, maxMessage: 'El campo "document_number" excede la longitud máxima permitida.')]
        public ?string $documentNumber,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['first_name'] ?? null),
            self::stringOrNull($payload['last_name'] ?? null),
            self::stringOrNull($payload['birth_date'] ?? null),
            self::stringOrNull($payload['document_number'] ?? null),
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
