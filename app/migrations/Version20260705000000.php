<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260705000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds player photo media columns';
    }

    public function up(Schema $schema): void
    {
        $columns = [];

        if (!$this->columnExists('players', 'photo_path')) {
            $columns[] = 'ADD photo_path VARCHAR(255) DEFAULT NULL';
        }

        if (!$this->columnExists('players', 'photo_url')) {
            $columns[] = 'ADD photo_url VARCHAR(255) DEFAULT NULL';
        }

        if (!$this->columnExists('players', 'photo_mime_type')) {
            $columns[] = 'ADD photo_mime_type VARCHAR(64) DEFAULT NULL';
        }

        if (!$this->columnExists('players', 'photo_size')) {
            $columns[] = 'ADD photo_size INT DEFAULT NULL';
        }

        if (!$this->columnExists('players', 'photo_checksum')) {
            $columns[] = 'ADD photo_checksum VARCHAR(255) DEFAULT NULL';
        }

        if ([] === $columns) {
            return;
        }

        $this->addSql(sprintf('ALTER TABLE players %s', implode(', ', $columns)));
    }

    public function down(Schema $schema): void
    {
        $columns = [];

        foreach ([
            'photo_path',
            'photo_url',
            'photo_mime_type',
            'photo_size',
            'photo_checksum',
        ] as $column) {
            if ($this->columnExists('players', $column)) {
                $columns[] = sprintf('DROP %s', $column);
            }
        }

        if ([] === $columns) {
            return;
        }

        $this->addSql(sprintf('ALTER TABLE players %s', implode(', ', $columns)));
    }

    private function columnExists(string $table, string $column): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$table, $column]
        );
    }
}
