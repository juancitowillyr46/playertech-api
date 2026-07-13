<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Presentation\Http\Request;

use App\Modules\Guardian\Application\Dto\CreateLegalGuardianInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateLegalGuardianRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "firstName" es obligatorio.')]
        #[Assert\Length(max: 100, maxMessage: 'El campo "firstName" excede la longitud máxima permitida.')]
        public ?string $firstName,

        #[Assert\NotBlank(message: 'El campo "lastName" es obligatorio.')]
        #[Assert\Length(max: 100, maxMessage: 'El campo "lastName" excede la longitud máxima permitida.')]
        public ?string $lastName,

        #[Assert\Length(max: 30, maxMessage: 'El campo "phone" excede la longitud máxima permitida.')]
        public ?string $phone = null,

        #[Assert\Email(message: 'El campo "email" debe ser un correo válido.')]
        #[Assert\Length(max: 255, maxMessage: 'El campo "email" excede la longitud máxima permitida.')]
        public ?string $email = null,

        #[Assert\NotBlank(message: 'El campo "relationship" es obligatorio.')]
        #[Assert\Length(max: 50, maxMessage: 'El campo "relationship" excede la longitud máxima permitida.')]
        public ?string $relationship = null,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['firstName'] ?? null),
            self::stringOrNull($payload['lastName'] ?? null),
            self::stringOrNull($payload['phone'] ?? null),
            self::stringOrNull($payload['email'] ?? null),
            self::stringOrNull($payload['relationship'] ?? null),
        );
    }

    public function toInput(): CreateLegalGuardianInput
    {
        return new CreateLegalGuardianInput(
            $this->firstName,
            $this->lastName,
            $this->phone,
            $this->email,
            $this->relationship,
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
