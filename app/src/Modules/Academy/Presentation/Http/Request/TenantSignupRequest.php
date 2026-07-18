<?php

declare(strict_types=1);

namespace App\Modules\Academy\Presentation\Http\Request;

use App\Modules\Academy\Application\Dto\TenantSignupInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class TenantSignupRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "name" es obligatorio.')]
        #[Assert\Length(max: 150, maxMessage: 'El campo "name" excede la longitud máxima permitida.')]
        public ?string $name,

        #[Assert\NotBlank(message: 'El campo "contactEmail" es obligatorio.')]
        #[Assert\Email(message: 'El campo "contactEmail" debe ser un correo válido.')]
        #[Assert\Length(max: 180, maxMessage: 'El campo "contactEmail" excede la longitud máxima permitida.')]
        public ?string $contactEmail,

        #[Assert\NotBlank(message: 'El campo "contactName" es obligatorio.')]
        #[Assert\Length(max: 150, maxMessage: 'El campo "contactName" excede la longitud máxima permitida.')]
        public ?string $contactName,

        #[Assert\NotBlank(message: 'El campo "password" es obligatorio.')]
        #[Assert\Length(min: 8, max: 255, minMessage: 'El campo "password" debe tener al menos 8 caracteres.', maxMessage: 'El campo "password" excede la longitud máxima permitida.')]
        public ?string $password,

        #[Assert\Length(max: 30, maxMessage: 'El campo "phone" excede la longitud máxima permitida.')]
        public ?string $phone = null,

        #[Assert\Length(max: 80, maxMessage: 'El campo "country" excede la longitud máxima permitida.')]
        public ?string $country = null,

        #[Assert\Length(max: 80, maxMessage: 'El campo "department" excede la longitud máxima permitida.')]
        public ?string $department = null,

        #[Assert\Length(max: 255, maxMessage: 'El campo "address" excede la longitud máxima permitida.')]
        public ?string $address = null,

        #[Assert\Length(max: 120, maxMessage: 'El campo "city" excede la longitud máxima permitida.')]
        public ?string $city = null,

        #[Assert\NotBlank(message: 'El campo "onboardingCategoryId" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "onboardingCategoryId" debe ser un UUID válido.')]
        public ?string $onboardingCategoryId = null,

        #[Assert\NotBlank(message: 'El campo "teamName" es obligatorio.')]
        #[Assert\Length(max: 80, maxMessage: 'El campo "teamName" excede la longitud máxima permitida.')]
        public ?string $teamName = null,

        #[Assert\IsTrue(message: 'Debe aceptar los términos y condiciones.')]
        public bool $acceptedTerms = false,

        #[Assert\IsTrue(message: 'Debe aceptar el tratamiento de datos personales.')]
        public bool $acceptedDataProcessing = false,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['name'] ?? null),
            self::stringOrNull($payload['contactEmail'] ?? null),
            self::stringOrNull($payload['contactName'] ?? null),
            self::stringOrNull($payload['password'] ?? null),
            self::stringOrNull($payload['phone'] ?? null),
            self::stringOrNull($payload['country'] ?? null),
            self::stringOrNull($payload['department'] ?? null),
            self::stringOrNull($payload['address'] ?? null),
            self::stringOrNull($payload['city'] ?? null),
            self::stringOrNull($payload['onboardingCategoryId'] ?? null),
            self::stringOrNull($payload['teamName'] ?? null),
            (bool) ($payload['acceptedTerms'] ?? false),
            (bool) ($payload['acceptedDataProcessing'] ?? false),
        );
    }

    public function toInput(): TenantSignupInput
    {
        return new TenantSignupInput(
            $this->name,
            $this->contactEmail,
            $this->contactName,
            $this->password,
            $this->phone,
            $this->country,
            $this->department,
            $this->address,
            $this->city,
            $this->onboardingCategoryId,
            $this->teamName,
            $this->acceptedTerms,
            $this->acceptedDataProcessing,
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
