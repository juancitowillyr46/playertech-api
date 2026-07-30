<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class DecimalFloatType extends Type
{
    public const NAME = 'decimal_float';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getDecimalTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        return null === $value ? null : (string) $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?float
    {
        return null === $value ? null : (float) $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
