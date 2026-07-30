<?php

declare(strict_types=1);

namespace App\Modules\Player\Infrastructure\Persistence\Doctrine\Type;

use App\Modules\Player\Domain\Document\PlayerDocumentId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class PlayerDocumentIdType extends Type
{
    public const NAME = 'player_document_id';
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string { return $platform->getGuidTypeDeclarationSQL($column); }
    public function convertToPHPValue($value, AbstractPlatform $platform): ?PlayerDocumentId { return null === $value ? null : new PlayerDocumentId((string) $value); }
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string { return null === $value ? null : (string) $value; }
    public function getName(): string { return self::NAME; }
}
