<?php

declare(strict_types=1);

namespace App\Modules\Academy\Presentation\Http\Request;

use App\Modules\Academy\Application\Dto\UpdateAcademyInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateAcademyRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "name" es obligatorio.')]
        #[Assert\Length(max: 150, maxMessage: 'El campo "name" excede la longitud máxima permitida.')]
        public ?string $name,

        #[Assert\NotBlank(message: 'El campo "contactEmail" es obligatorio.')]
        #[Assert\Email(message: 'El campo "contactEmail" debe ser un correo válido.')]
        #[Assert\Length(max: 180, maxMessage: 'El campo "contactEmail" excede la longitud máxima permitida.')]
        public ?string $contactEmail,

        #[Assert\Length(max: 30, maxMessage: 'El campo "phone" excede la longitud máxima permitida.')]
        public ?string $phone = null,

        #[Assert\Length(max: 80, maxMessage: 'El campo "country" excede la longitud máxima permitida.')]
        public ?string $country = null,

        #[Assert\Length(max: 80, maxMessage: 'El campo "department" excede la longitud máxima permitida.')]
        public ?string $department = null,

        #[Assert\Length(max: 40, maxMessage: 'El campo "taxIdType" excede la longitud máxima permitida.')]
        public ?string $taxIdType = null,

        #[Assert\Length(max: 40, maxMessage: 'El campo "taxIdNumber" excede la longitud máxima permitida.')]
        public ?string $taxIdNumber = null,

        #[Assert\Length(max: 80, maxMessage: 'El campo "taxRegime" excede la longitud máxima permitida.')]
        public ?string $taxRegime = null,

        #[Assert\Email(message: 'El campo "billingEmail" debe ser un correo válido.')]
        #[Assert\Length(max: 180, maxMessage: 'El campo "billingEmail" excede la longitud máxima permitida.')]
        public ?string $billingEmail = null,

        #[Assert\Length(max: 255, maxMessage: 'El campo "address" excede la longitud máxima permitida.')]
        public ?string $address = null,

        #[Assert\Length(max: 120, maxMessage: 'El campo "city" excede la longitud máxima permitida.')]
        public ?string $city = null,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['name'] ?? null),
            self::stringOrNull($payload['contactEmail'] ?? null),
            self::stringOrNull($payload['phone'] ?? null),
            self::stringOrNull($payload['country'] ?? null),
            self::stringOrNull($payload['department'] ?? null),
            self::stringOrNull($payload['taxIdType'] ?? null),
            self::stringOrNull($payload['taxIdNumber'] ?? null),
            self::stringOrNull($payload['taxRegime'] ?? null),
            self::stringOrNull($payload['billingEmail'] ?? null),
            self::stringOrNull($payload['address'] ?? null),
            self::stringOrNull($payload['city'] ?? null),
        );
    }

    public function toInput(): UpdateAcademyInput
    {
        return new UpdateAcademyInput(
            $this->name,
            $this->contactEmail,
            $this->phone,
            $this->country,
            $this->department,
            $this->taxIdType,
            $this->taxIdNumber,
            $this->taxRegime,
            $this->billingEmail,
            $this->address,
            $this->city,
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
