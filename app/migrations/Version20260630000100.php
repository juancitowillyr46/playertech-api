<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260630000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add category business key for category API and bulk import.';
    }

    public function up(Schema $schema): void
    {
        if (!$this->columnExists('categories', 'category_key')) {
            $this->addSql('ALTER TABLE categories ADD category_key VARCHAR(50) DEFAULT NULL');
        }

        $this->addSql("UPDATE categories SET category_key = LEFT(CONCAT(UPPER(REPLACE(REPLACE(name, ' ', '_'), '-', '_')), '_', REPLACE(LEFT(id, 8), '-', '')), 50) WHERE category_key IS NULL OR category_key = ''");

        $this->addSql('ALTER TABLE categories MODIFY category_key VARCHAR(50) NOT NULL');

        if (!$this->indexExists('categories', 'UNIQ_CATEGORY_ACADEMY_KEY')) {
            $this->addSql('CREATE UNIQUE INDEX UNIQ_CATEGORY_ACADEMY_KEY ON categories (academy_id, category_key)');
        }
    }

    public function down(Schema $schema): void
    {
        if ($this->indexExists('categories', 'UNIQ_CATEGORY_ACADEMY_KEY')) {
            $this->addSql('DROP INDEX UNIQ_CATEGORY_ACADEMY_KEY ON categories');
        }

        if ($this->columnExists('categories', 'category_key')) {
            $this->addSql('ALTER TABLE categories DROP category_key');
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
