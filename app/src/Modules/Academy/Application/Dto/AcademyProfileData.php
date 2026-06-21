<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Dto;

use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\LogoPath;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final readonly class AcademyProfileData
{
    public function __construct(
        private Name $name,
        private Email $contactEmail,
        private ?PhoneNumber $phone,
        private ?Address $address,
        private ?City $city,
        private ?LogoPath $logo,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::requiredName($payload),
            self::requiredEmail($payload),
            self::optionalPhone($payload),
            self::optionalAddress($payload),
            self::optionalCity($payload),
            self::optionalLogo($payload),
        );
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function contactEmail(): Email
    {
        return $this->contactEmail;
    }

    public function phone(): ?PhoneNumber
    {
        return $this->phone;
    }

    public function address(): ?Address
    {
        return $this->address;
    }

    public function city(): ?City
    {
        return $this->city;
    }

    public function logo(): ?LogoPath
    {
        return $this->logo;
    }

    private static function requiredName(array $payload): Name
    {
        return new Name(self::requiredString($payload, 'name', 150));
    }

    private static function requiredEmail(array $payload): Email
    {
        return new Email(self::requiredString($payload, 'contact_email', 180));
    }

    private static function optionalPhone(array $payload): ?PhoneNumber
    {
        $value = self::optionalString($payload, 'phone', 30);

        return null === $value ? null : new PhoneNumber($value);
    }

    private static function optionalAddress(array $payload): ?Address
    {
        $value = self::optionalString($payload, 'address', 255);

        return null === $value ? null : new Address($value);
    }

    private static function optionalCity(array $payload): ?City
    {
        $value = self::optionalString($payload, 'city', 120);

        return null === $value ? null : new City($value);
    }

    private static function optionalLogo(array $payload): ?LogoPath
    {
        $value = self::optionalString($payload, 'logo', 255);

        return null === $value ? null : new LogoPath($value);
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

        if ('contact_email' === $key && false === filter_var($value, FILTER_VALIDATE_EMAIL)) {
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
