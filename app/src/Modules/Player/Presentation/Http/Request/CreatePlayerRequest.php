<?php

declare(strict_types=1);

namespace App\Modules\Player\Presentation\Http\Request;

use App\Modules\Player\Application\Dto\CreatePlayerInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreatePlayerRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "firstName" es obligatorio.')]
        #[Assert\Length(max: 100, maxMessage: 'El campo "firstName" excede la longitud máxima permitida.')]
        public ?string $firstName,

        #[Assert\NotBlank(message: 'El campo "lastName" es obligatorio.')]
        #[Assert\Length(max: 100, maxMessage: 'El campo "lastName" excede la longitud máxima permitida.')]
        public ?string $lastName,

        #[Assert\NotBlank(message: 'El campo "birthDate" es obligatorio.')]
        #[Assert\Date(message: 'El campo "birthDate" debe tener un formato de fecha válido.')]
        public ?string $birthDate,

        #[Assert\NotBlank(message: 'El campo "documentNumber" es obligatorio.')]
        #[Assert\Length(max: 30, maxMessage: 'El campo "documentNumber" excede la longitud máxima permitida.')]
        public ?string $documentNumber,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['firstName'] ?? null),
            self::stringOrNull($payload['lastName'] ?? null),
            self::stringOrNull($payload['birthDate'] ?? null),
            self::stringOrNull($payload['documentNumber'] ?? null),
        );
    }

    public function toInput(): CreatePlayerInput
    {
        return new CreatePlayerInput(
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
