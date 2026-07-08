<?php

declare(strict_types=1);

namespace App\Modules\Player\Presentation\Http\Request;

use App\Modules\Player\Application\Dto\UpdatePlayerInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdatePlayerRequest
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

    public function toInput(): UpdatePlayerInput
    {
        return new UpdatePlayerInput(
            $this->firstName,
            $this->lastName,
            $this->birthDate,
            $this->documentNumber,
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
