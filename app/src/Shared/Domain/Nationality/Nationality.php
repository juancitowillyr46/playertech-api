<?php

declare(strict_types=1);

namespace App\Shared\Domain\Nationality;

enum Nationality: string
{
    case COLOMBIAN = 'COLOMBIAN';
    case PERUVIAN = 'PERUVIAN';
    case CHILEAN = 'CHILEAN';
    case ECUADORIAN = 'ECUADORIAN';
    case MEXICAN = 'MEXICAN';
    case SPANISH = 'SPANISH';

    public function label(): string
    {
        return match ($this) {
            self::COLOMBIAN => 'Colombiano(a)',
            self::PERUVIAN => 'Peruano(a)',
            self::CHILEAN => 'Chileno(a)',
            self::ECUADORIAN => 'Ecuatoriano(a)',
            self::MEXICAN => 'Mexicano(a)',
            self::SPANISH => 'Español(a)',
        };
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    public static function options(): array
    {
        return array_map(
            static fn (self $nationality): array => [
                'label' => $nationality->label(),
                'value' => $nationality->value,
            ],
            self::cases(),
        );
    }

    public static function fromInput(string $value): self
    {
        return self::tryFrom(strtoupper(trim($value)))
            ?? throw new \InvalidArgumentException('La nacionalidad no es válida.');
    }
}
