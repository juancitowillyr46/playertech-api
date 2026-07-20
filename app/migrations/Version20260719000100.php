<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260719000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Extend venues with country, department and primary flag for tenant signup.';
    }

    public function up(Schema $schema): void
    {
        if (!$this->tableExists('venues')) {
            return;
        }

        if (!$this->columnExists('venues', 'country')) {
            $this->addSql('ALTER TABLE venues ADD country VARCHAR(80) DEFAULT NULL AFTER city');
        }

        if (!$this->columnExists('venues', 'department')) {
            $this->addSql('ALTER TABLE venues ADD department VARCHAR(80) DEFAULT NULL AFTER country');
        }

        if (!$this->columnExists('venues', 'is_primary')) {
            $this->addSql('ALTER TABLE venues ADD is_primary TINYINT(1) NOT NULL DEFAULT 0 AFTER notes');
        }
    }

    public function down(Schema $schema): void
    {
        if (!$this->tableExists('venues')) {
            return;
        }

        if ($this->columnExists('venues', 'is_primary')) {
            $this->addSql('ALTER TABLE venues DROP COLUMN is_primary');
        }

        if ($this->columnExists('venues', 'department')) {
            $this->addSql('ALTER TABLE venues DROP COLUMN department');
        }

        if ($this->columnExists('venues', 'country')) {
            $this->addSql('ALTER TABLE venues DROP COLUMN country');
        }
    }

    private function tableExists(string $table): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
            [$table]
        );
    }

    private function columnExists(string $table, string $column): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$table, $column]
        );
    }
}
