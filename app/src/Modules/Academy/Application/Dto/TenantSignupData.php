<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Dto;

use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final readonly class TenantSignupData
{
    public function __construct(
        private Name $academyName,
        private Email $contactEmail,
        private string $contactName,
        private string $plainPassword,
        private ?PhoneNumber $phone,
        private ?City $city,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            new Name(self::requiredString($payload, 'name', 150)),
            new Email(self::requiredString($payload, 'contactEmail', 180)),
            self::requiredString($payload, 'contactName', 150),
            self::requiredString($payload, 'password', 255),
            self::optionalPhone($payload),
            self::optionalCity($payload),
        );
    }

    public function academyName(): Name
    {
        return $this->academyName;
    }

    public function contactEmail(): Email
    {
        return $this->contactEmail;
    }

    public function contactName(): string
    {
        return $this->contactName;
    }

    public function plainPassword(): string
    {
        return $this->plainPassword;
    }

    public function phone(): ?PhoneNumber
    {
        return $this->phone;
    }

    public function city(): ?City
    {
        return $this->city;
    }

    private static function optionalPhone(array $payload): ?PhoneNumber
    {
        $value = self::optionalString($payload, 'phone', 30);

        return null === $value ? null : new PhoneNumber($value);
    }

    private static function optionalCity(array $payload): ?City
    {
        $value = self::optionalString($payload, 'city', 120);

        return null === $value ? null : new City($value);
    }

    private static function requiredString(array $payload, string $key, int $maxLength): string
    {
        if (!array_key_exists($key, $payload)) {
            throw new BadRequestHttpException(sprintf('El campo "%s" es obligatorio.', $key));
        }

        $value = trim((string) $payload[$key]);

        if ('' === $value) {
            throw new BadRequestHttpException(sprintf('El campo "%s" es obligatorio.', $key));
        }

        if (mb_strlen($value) > $maxLength) {
            throw new BadRequestHttpException(sprintf('El campo "%s" excede la longitud máxima permitida.', $key));
        }

        if ('contactEmail' === $key && false === filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new BadRequestHttpException(sprintf('El campo "%s" debe ser un correo válido.', $key));
        }

        return $value;
    }

    private static function optionalString(array $payload, string $key, int $maxLength): ?string
    {
        if (!array_key_exists($key, $payload) || null === $payload[$key]) {
            return null;
        }

        $value = trim((string) $payload[$key]);

        if ('' === $value) {
            return null;
        }

        if (mb_strlen($value) > $maxLength) {
            throw new BadRequestHttpException(sprintf('El campo "%s" excede la longitud máxima permitida.', $key));
        }

        return $value;
    }
}
