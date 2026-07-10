<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260708000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add basic location fields to academies for MVP profile enrichment.';
    }

    public function up(Schema $schema): void
    {
        if (!$this->columnExists('academies', 'country')) {
            $this->addSql('ALTER TABLE academies ADD country VARCHAR(80) DEFAULT NULL AFTER phone');
        }

        if (!$this->columnExists('academies', 'department')) {
            $this->addSql('ALTER TABLE academies ADD department VARCHAR(80) DEFAULT NULL AFTER country');
        }
    }

    public function down(Schema $schema): void
    {
        if ($this->columnExists('academies', 'department')) {
            $this->addSql('ALTER TABLE academies DROP department');
        }

        if ($this->columnExists('academies', 'country')) {
            $this->addSql('ALTER TABLE academies DROP country');
        }
    }

    private function columnExists(string $table, string $column): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$table, $column]
        );
    }
}
