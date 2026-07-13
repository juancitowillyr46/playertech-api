<?php

declare(strict_types=1);

namespace App\Modules\Academy\Presentation\Http\Request;

use App\Modules\Academy\Application\Dto\ProvisionTenantInput;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ProvisionTenantRequest
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

        #[Assert\Length(max: 255, maxMessage: 'El campo "address" excede la longitud máxima permitida.')]
        public ?string $address = null,

        #[Assert\Length(max: 120, maxMessage: 'El campo "city" excede la longitud máxima permitida.')]
        public ?string $city = null,

        #[Assert\NotBlank(message: 'El campo "adminName" es obligatorio.')]
        #[Assert\Length(max: 150, maxMessage: 'El campo "adminName" excede la longitud máxima permitida.')]
        public ?string $adminName = null,

        #[Assert\NotBlank(message: 'El campo "adminEmail" es obligatorio.')]
        #[Assert\Email(message: 'El campo "adminEmail" debe ser un correo válido.')]
        #[Assert\Length(max: 180, maxMessage: 'El campo "adminEmail" excede la longitud máxima permitida.')]
        public ?string $adminEmail = null,

        #[Assert\NotBlank(message: 'El campo "categoryId" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "categoryId" debe ser un UUID válido.')]
        public ?string $categoryId = null,

        #[Assert\NotBlank(message: 'El campo "teamName" es obligatorio.')]
        #[Assert\Length(max: 80, maxMessage: 'El campo "teamName" excede la longitud máxima permitida.')]
        public ?string $teamName = null,
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
            self::stringOrNull($payload['address'] ?? null),
            self::stringOrNull($payload['city'] ?? null),
            self::stringOrNull($payload['adminName'] ?? null),
            self::stringOrNull($payload['adminEmail'] ?? null),
            self::stringOrNull($payload['categoryId'] ?? null),
            self::stringOrNull($payload['teamName'] ?? null),
        );
    }

    public function toInput(): ProvisionTenantInput
    {
        return new ProvisionTenantInput(
            $this->name,
            $this->contactEmail,
            $this->phone,
            $this->country,
            $this->department,
            $this->address,
            $this->city,
            $this->adminName,
            $this->adminEmail,
            $this->categoryId,
            $this->teamName,
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
