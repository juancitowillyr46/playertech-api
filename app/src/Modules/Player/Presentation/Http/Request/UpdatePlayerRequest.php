<?php

declare(strict_types=1);

namespace App\Modules\Player\Presentation\Http\Request;

use App\Modules\Player\Application\Dto\UpdatePlayerInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdatePlayerRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "documentType" es obligatorio.')]
        #[Assert\Length(max: 50, maxMessage: 'El campo "documentType" excede la longitud máxima permitida.')]
        public ?string $documentType,

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

        #[Assert\Email(message: 'El campo "email" debe tener un formato válido.')]
        #[Assert\Length(max: 255, maxMessage: 'El campo "email" excede la longitud máxima permitida.')]
        public ?string $email = null,

        #[Assert\Length(max: 50, maxMessage: 'El campo "phone" excede la longitud máxima permitida.')]
        public ?string $phone = null,

        #[Assert\Length(max: 100, maxMessage: 'El campo "nationality" excede la longitud máxima permitida.')]
        public ?string $nationality = null,

        #[Assert\Length(max: 20, maxMessage: 'El campo "gender" excede la longitud máxima permitida.')]
        public ?string $gender = null,

        #[Assert\Length(max: 80, maxMessage: 'El campo "federationId" excede la longitud máxima permitida.')]
        public ?string $federationId = null,

        #[Assert\Length(max: 20, maxMessage: 'El campo "dominantFoot" excede la longitud máxima permitida.')]
        public ?string $dominantFoot = null,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['documentType'] ?? null),
            self::stringOrNull($payload['firstName'] ?? null),
            self::stringOrNull($payload['lastName'] ?? null),
            self::stringOrNull($payload['birthDate'] ?? null),
            self::stringOrNull($payload['documentNumber'] ?? null),
            self::stringOrNull($payload['email'] ?? null),
            self::stringOrNull($payload['phone'] ?? null),
            self::stringOrNull($payload['nationality'] ?? null),
            self::stringOrNull($payload['gender'] ?? null),
            self::stringOrNull($payload['federationId'] ?? null),
            self::stringOrNull($payload['dominantFoot'] ?? null),
        );
    }

    public function toInput(): UpdatePlayerInput
    {
        return new UpdatePlayerInput(
            $this->documentType,
            $this->firstName,
            $this->lastName,
            $this->birthDate,
            $this->documentNumber,
            $this->email,
            $this->phone,
            $this->nationality,
            $this->gender,
            $this->federationId,
            $this->dominantFoot,
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
