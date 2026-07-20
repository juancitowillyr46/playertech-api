<?php

declare(strict_types=1);

namespace App\Tests\Support\Database;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use RuntimeException;

final class SchemaResetter
{
    /**
     * Drops and recreates the provided metadata inside the dedicated test database.
     *
     * This is intentionally explicit so only schema-oriented tests opt into a reset.
     */
    public static function reset(EntityManagerInterface $entityManager, array $metadata): void
    {
        self::assertSafeDatabase($entityManager);

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    public static function create(EntityManagerInterface $entityManager, array $metadata): void
    {
        self::assertSafeDatabase($entityManager);

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->createSchema($metadata);
    }

    private static function assertSafeDatabase(EntityManagerInterface $entityManager): void
    {
        $databaseName = (string) $entityManager->getConnection()->getDatabase();

        if ('' === $databaseName || !str_ends_with($databaseName, '_test')) {
            throw new RuntimeException(sprintf(
                'Refusing to reset schema outside the test database. Current database: "%s".',
                $databaseName
            ));
        }
    }
}
