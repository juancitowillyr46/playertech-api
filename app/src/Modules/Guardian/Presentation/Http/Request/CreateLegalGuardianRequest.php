<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Presentation\Http\Request;

use App\Modules\Guardian\Application\Dto\CreateLegalGuardianInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateLegalGuardianRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "first_name" es obligatorio.')]
        #[Assert\Length(max: 100, maxMessage: 'El campo "first_name" excede la longitud máxima permitida.')]
        public ?string $firstName,

        #[Assert\NotBlank(message: 'El campo "last_name" es obligatorio.')]
        #[Assert\Length(max: 100, maxMessage: 'El campo "last_name" excede la longitud máxima permitida.')]
        public ?string $lastName,

        #[Assert\Length(max: 30, maxMessage: 'El campo "phone" excede la longitud máxima permitida.')]
        public ?string $phone = null,

        #[Assert\Email(message: 'El campo "email" debe ser un correo válido.')]
        #[Assert\Length(max: 255, maxMessage: 'El campo "email" excede la longitud máxima permitida.')]
        public ?string $email = null,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['first_name'] ?? null),
            self::stringOrNull($payload['last_name'] ?? null),
            self::stringOrNull($payload['phone'] ?? null),
            self::stringOrNull($payload['email'] ?? null),
        );
    }

    public function toInput(): CreateLegalGuardianInput
    {
        return new CreateLegalGuardianInput(
            $this->firstName,
            $this->lastName,
            $this->phone,
            $this->email,
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
