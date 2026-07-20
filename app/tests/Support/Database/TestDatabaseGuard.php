<?php

declare(strict_types=1);

namespace App\Tests\Support\Database;

use RuntimeException;

final class TestDatabaseGuard
{
    public static function assertTestDatabase(string $databaseUrl): void
    {
        if ('' === $databaseUrl || !str_starts_with($databaseUrl, 'mysql://')) {
            return;
        }

        $parts = parse_url($databaseUrl);
        $databaseName = isset($parts['path']) ? ltrim((string) $parts['path'], '/') : '';

        if ('' === $databaseName) {
            return;
        }

        if (!str_ends_with($databaseName, '_test')) {
            throw new RuntimeException(sprintf(
                'Refusing to use non-test database "%s" during test execution.',
                $databaseName
            ));
        }
    }
}
