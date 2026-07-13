<?php

declare(strict_types=1);

namespace App\Modules\Academy\Domain\Academy;

final readonly class AcademyRegistrationSource
{
    public const SIGNUP = 'signup';
    public const PLATFORM = 'platform';

    private function __construct(
        private string $value,
    ) {
    }

    public static function signup(): self
    {
        return new self(self::SIGNUP);
    }

    public static function platform(): self
    {
        return new self(self::PLATFORM);
    }

    public static function fromString(string $value): self
    {
        return match ($value) {
            self::SIGNUP => self::signup(),
            self::PLATFORM => self::platform(),
            default => throw new \InvalidArgumentException(sprintf('Origen de registro de academia inválido: %s', $value)),
        };
    }

    public function value(): string
    {
        return $this->value;
    }
}
