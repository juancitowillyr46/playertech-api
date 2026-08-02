<?php

declare(strict_types=1);

namespace App\Shared\Domain\Relationship;

enum Relationship: string
{
    case FATHER = 'FATHER';
    case MOTHER = 'MOTHER';
    case GRANDFATHER = 'GRANDFATHER';
    case GRANDMA = 'GRANDMA';
    case TUTOR = 'TUTOR';
    case BROTHER = 'BROTHER';
    case SISTER = 'SISTER';
    case OTHER = 'OTHER';

    public function label(): string
    {
        return match ($this) {
            self::FATHER => 'Padre',
            self::MOTHER => 'Madre',
            self::GRANDFATHER => 'Abuelo',
            self::GRANDMA => 'Abuela',
            self::TUTOR => 'Tutor',
            self::BROTHER => 'Hermano',
            self::SISTER => 'Hermana',
            self::OTHER => 'Otro',
        };
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    public static function options(): array
    {
        return array_map(
            static fn (self $relationship): array => [
                'label' => $relationship->label(),
                'value' => $relationship->value,
            ],
            self::cases(),
        );
    }

    public static function fromInput(string $value): self
    {
        return self::tryFrom(strtoupper(trim($value)))
            ?? throw new \InvalidArgumentException('El parentesco no es válido.');
    }
}
