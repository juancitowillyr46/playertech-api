<?php

declare(strict_types=1);

require dirname(__DIR__).'/vendor/autoload.php';

$databaseName = 'playertech_test_'.getmypid();
$dsn = 'mysql:host=mysql;port=3306;charset=utf8mb4';

$pdo = new PDO($dsn, 'root', 'root', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$pdo->exec(sprintf(
    'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
    $databaseName
));

$databaseUrl = sprintf(
    'mysql://root:root@mysql:3306/%s?serverVersion=8.0&charset=utf8mb4',
    $databaseName
);

putenv('DATABASE_URL='.$databaseUrl);
$_ENV['DATABASE_URL'] = $databaseUrl;
$_SERVER['DATABASE_URL'] = $databaseUrl;
