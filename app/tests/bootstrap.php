<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->usePutenv();
$projectDir = dirname(__DIR__);
$dotenv->loadEnv($projectDir.'/.env');

if ('test' === ($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null) && is_file($projectDir.'/.env.test')) {
    $dotenv->overload($projectDir.'/.env.test');
}

$databaseUrl = $_SERVER['DATABASE_URL'] ?? $_ENV['DATABASE_URL'] ?? null;

if ('test' === ($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null) && is_string($databaseUrl) && str_starts_with($databaseUrl, 'mysql://')) {
    $parts = parse_url($databaseUrl);
    $databaseName = isset($parts['path']) ? ltrim((string) $parts['path'], '/') : null;

    if (is_string($databaseName) && '' !== $databaseName) {
        if (!str_ends_with($databaseName, '_test')) {
            throw new RuntimeException(sprintf(
                'PHPUnit must use a test database. Current DATABASE_URL points to "%s".',
                $databaseName
            ));
        }

        $pdo = new PDO('mysql:host=mysql;port=3306;charset=utf8mb4', 'root', 'root', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        $pdo->exec(sprintf(
            'DROP DATABASE IF EXISTS `%s`',
            $databaseName
        ));

        $pdo->exec(sprintf(
            'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
            $databaseName
        ));
    }
}
