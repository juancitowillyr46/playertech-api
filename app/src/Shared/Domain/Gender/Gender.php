<?php

declare(strict_types=1);

namespace App\Shared\Domain\Gender;

enum Gender: string
{
    case MALE = 'MALE';
    case FEMALE = 'FEMALE';

    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Masculino',
            self::FEMALE => 'Femenino',
        };
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    public static function options(): array
    {
        return array_map(
            static fn (self $gender): array => [
                'label' => $gender->label(),
                'value' => $gender->value,
            ],
            self::cases(),
        );
    }

    public static function fromInput(string $value): self
    {
        return self::tryFrom(strtoupper(trim($value)))
            ?? throw new \InvalidArgumentException('El género no es válido.');
    }
}
