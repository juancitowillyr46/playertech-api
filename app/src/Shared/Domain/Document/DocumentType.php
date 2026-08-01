<?php

declare(strict_types=1);

namespace App\Shared\Domain\Document;

enum DocumentType: string
{
    case CE = 'CE';
    case CC = 'CC';
    case TI = 'TI';
    case PPT = 'PPT';
    case PASSPORT = 'PASSPORT';
    case RC = 'RC';

    public function label(): string
    {
        return match ($this) {
            self::CE => 'Cédula de extranjería',
            self::CC => 'Cédula de ciudadanía',
            self::TI => 'Tarjeta de identidad',
            self::PPT => 'PPT',
            self::PASSPORT => 'Pasaporte',
            self::RC => 'Registro civil',
        };
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    public static function options(): array
    {
        return array_map(
            static fn (self $type): array => [
                'label' => $type->label(),
                'value' => $type->value,
            ],
            self::cases(),
        );
    }

    public static function fromInput(string $value): self
    {
        return self::tryFrom(strtoupper(trim($value)))
            ?? throw new \InvalidArgumentException('El tipo de documento no es válido.');
    }
}
