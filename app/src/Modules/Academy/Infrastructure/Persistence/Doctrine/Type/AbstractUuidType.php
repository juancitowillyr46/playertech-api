<?php

declare(strict_types=1);

namespace App\Modules\Academy\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use Stringable;

abstract class AbstractUuidType extends Type
{
    abstract protected function getValueObjectClass(): string;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        if ($value instanceof Stringable) {
            return (string) $value;
        }

        throw new InvalidArgumentException(sprintf(
            'Valor inválido para %s. Se esperaba string o Stringable, se recibió %s.',
            static::class,
            get_debug_type($value)
        ));
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if (null === $value) {
            return null;
        }

        $class = $this->getValueObjectClass();

        if ($value instanceof $class) {
            return $value;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException(sprintf(
                'Valor inválido para %s. Se esperaba string, se recibió %s.',
                static::class,
                get_debug_type($value)
            ));
        }

        return new $class($value);
    }
}
