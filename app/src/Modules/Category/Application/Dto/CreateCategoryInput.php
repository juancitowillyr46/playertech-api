<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateCategoryInput
{
    public function __construct(
        #[Assert\NotBlank(message: 'El campo "name" es obligatorio.')]
        #[Assert\Length(max: 100)]
        public ?string $name,

        #[Assert\NotNull(message: 'El campo "min_age" es obligatorio.')]
        #[Assert\Range(min: 0, max: 100)]
        public ?int $minAge,

        #[Assert\NotNull(message: 'El campo "max_age" es obligatorio.')]
        #[Assert\Range(min: 0, max: 100)]
        public ?int $maxAge,

        #[Assert\Length(max: 250)]
        public ?string $description = null,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            self::stringOrNull($payload['name'] ?? null),
            self::intOrNull($payload['minAge'] ?? null),
            self::intOrNull($payload['maxAge'] ?? null),
            self::stringOrNull($payload['description'] ?? null),
        );
    }

    private static function stringOrNull(mixed $value): ?string
    {
        if (null === $value) {
            return null;
        }

        $value = trim((string) $value);

        return '' === $value ? null : $value;
    }

    private static function intOrNull(mixed $value): ?int
    {
        if (null === $value || '' === $value) {
            return null;
        }

        return (int) $value;
    }
}