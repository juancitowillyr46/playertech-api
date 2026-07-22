<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final readonly class PhoneNumber
{
    private ?string $value;

    public function __construct(?string $value)
    {
        if (null === $value) {
            $this->value = null;

            return;
        }

        $normalized = self::normalize($value);

        if (mb_strlen($normalized) > 30) {
            throw new \InvalidArgumentException('Phone number too long.');
        }

        $this->value = $normalized;
    }

    public function value(): ?string
    {
        return $this->value;
    }

    private static function normalize(string $value): string
    {
        $normalized = preg_replace('/[\s\-\(\)]/', '', trim($value));

        return null === $normalized ? trim($value) : $normalized;
    }
}
