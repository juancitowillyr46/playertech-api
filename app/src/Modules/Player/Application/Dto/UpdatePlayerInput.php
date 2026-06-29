<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Dto;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final readonly class UpdatePlayerInput
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $birthDate,
        private string $documentNumber,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::requiredString($payload, 'first_name', 100),
            self::requiredString($payload, 'last_name', 100),
            self::requiredDate($payload, 'birth_date'),
            self::requiredString($payload, 'document_number', 30),
        );
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function birthDate(): string
    {
        return $this->birthDate;
    }

    public function documentNumber(): string
    {
        return $this->documentNumber;
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

        return $value;
    }

    private static function requiredDate(array $payload, string $key): string
    {
        $value = self::requiredString($payload, $key, 10);
        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $value);

        if (false === $date || $date->format('Y-m-d') !== $value) {
            throw new BadRequestHttpException(sprintf('El campo "%s" debe tener formato Y-m-d.', $key));
        }

        return $value;
    }
}
