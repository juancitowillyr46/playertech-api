<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260621000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add soft delete columns to academies';
    }

    public function up(Schema $schema): void
    {
        if (!$this->columnExists('academies', 'deleted_at')) {
            $this->addSql('ALTER TABLE academies ADD deleted_at DATETIME DEFAULT NULL');
        }

        if (!$this->columnExists('academies', 'deleted_by')) {
            $this->addSql('ALTER TABLE academies ADD deleted_by CHAR(36) DEFAULT NULL');
        }

        if (!$this->indexExists('academies', 'IDX_ACADEMIES_DELETED_BY')) {
            $this->addSql('CREATE INDEX IDX_ACADEMIES_DELETED_BY ON academies (deleted_by)');
        }
    }

    public function down(Schema $schema): void
    {
        if ($this->indexExists('academies', 'IDX_ACADEMIES_DELETED_BY')) {
            $this->addSql('DROP INDEX IDX_ACADEMIES_DELETED_BY ON academies');
        }

        $dropColumns = [];

        if ($this->columnExists('academies', 'deleted_at')) {
            $dropColumns[] = 'DROP deleted_at';
        }

        if ($this->columnExists('academies', 'deleted_by')) {
            $dropColumns[] = 'DROP deleted_by';
        }

        if ([] !== $dropColumns) {
            $this->addSql(sprintf('ALTER TABLE academies %s', implode(', ', $dropColumns)));
        }
    }

    private function columnExists(string $table, string $column): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$table, $column]
        );
    }

    private function indexExists(string $table, string $index): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?',
            [$table, $index]
        );
    }
}
