<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class TenantSignupInput
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "name" es obligatorio.')]
        #[Assert\Length(max: 150, maxMessage: 'El campo "name" excede la longitud máxima permitida.')]
        public ?string $name,

        #[Assert\NotBlank(message: 'El campo "contact_email" es obligatorio.')]
        #[Assert\Email(message: 'El campo "contact_email" debe ser un correo válido.')]
        #[Assert\Length(max: 180, maxMessage: 'El campo "contact_email" excede la longitud máxima permitida.')]
        public ?string $contactEmail,

        #[Assert\NotBlank(message: 'El campo "contact_name" es obligatorio.')]
        #[Assert\Length(max: 150, maxMessage: 'El campo "contact_name" excede la longitud máxima permitida.')]
        public ?string $contactName,

        #[Assert\NotBlank(message: 'El campo "password" es obligatorio.')]
        #[Assert\Length(min: 8, max: 255, minMessage: 'El campo "password" debe tener al menos 8 caracteres.', maxMessage: 'El campo "password" excede la longitud máxima permitida.')]
        public ?string $password,

        #[Assert\Length(max: 30, maxMessage: 'El campo "phone" excede la longitud máxima permitida.')]
        public ?string $phone = null,

        #[Assert\Length(max: 255, maxMessage: 'El campo "address" excede la longitud máxima permitida.')]
        public ?string $address = null,

        #[Assert\Length(max: 120, maxMessage: 'El campo "city" excede la longitud máxima permitida.')]
        public ?string $city = null,

        #[Assert\NotBlank(message: 'El campo "category_id" es obligatorio.')]
        #[Assert\Uuid(message: 'El campo "category_id" debe ser un UUID válido.')]
        public ?string $categoryId = null,

        #[Assert\NotBlank(message: 'El campo "team_name" es obligatorio.')]
        #[Assert\Length(max: 80, maxMessage: 'El campo "team_name" excede la longitud máxima permitida.')]
        public ?string $teamName = null,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['name'] ?? null),
            self::stringOrNull($payload['contact_email'] ?? null),
            self::stringOrNull($payload['contact_name'] ?? null),
            self::stringOrNull($payload['password'] ?? null),
            self::stringOrNull($payload['phone'] ?? null),
            self::stringOrNull($payload['address'] ?? null),
            self::stringOrNull($payload['city'] ?? null),
            self::stringOrNull($payload['category_id'] ?? $payload['categoryId'] ?? null),
            self::stringOrNull($payload['team_name'] ?? $payload['teamName'] ?? null),
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
