<?php

declare(strict_types=1);

namespace App\Shared\Domain\DominantFoot;

enum DominantFoot: string
{
    case RIGHT = 'RIGHT';
    case LEFT = 'LEFT';
    case BOTH = 'BOTH';

    public function label(): string
    {
        return match ($this) {
            self::RIGHT => 'Pie derecho',
            self::LEFT => 'Pie izquierdo',
            self::BOTH => 'Ambidiestro',
        };
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    public static function options(): array
    {
        return array_map(
            static fn (self $dominantFoot): array => [
                'label' => $dominantFoot->label(),
                'value' => $dominantFoot->value,
            ],
            self::cases(),
        );
    }

    public static function fromInput(string $value): self
    {
        return self::tryFrom(strtoupper(trim($value)))
            ?? throw new \InvalidArgumentException('El pie dominante no es válido.');
    }
}
