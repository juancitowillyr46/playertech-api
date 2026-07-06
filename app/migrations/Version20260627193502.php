<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260627193502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if ($this->indexExists('categories', 'UNIQ_CATEGORY_ACADEMY_NAME')) {
            $this->addSql('DROP INDEX UNIQ_CATEGORY_ACADEMY_NAME ON categories');
        }

        if (!$this->indexExists('categories', 'IDX_CATEGORY_CREATED_BY')) {
            $this->addSql('CREATE INDEX IDX_CATEGORY_CREATED_BY ON categories (created_by)');
        }

        if (!$this->indexExists('categories', 'IDX_CATEGORY_UPDATED_BY')) {
            $this->addSql('CREATE INDEX IDX_CATEGORY_UPDATED_BY ON categories (updated_by)');
        }

        if (!$this->indexExists('categories', 'IDX_CATEGORY_DELETED_BY')) {
            $this->addSql('CREATE INDEX IDX_CATEGORY_DELETED_BY ON categories (deleted_by)');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_CATEGORY_CREATED_BY ON categories');
        $this->addSql('DROP INDEX IDX_CATEGORY_UPDATED_BY ON categories');
        $this->addSql('DROP INDEX IDX_CATEGORY_DELETED_BY ON categories');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CATEGORY_ACADEMY_NAME ON categories (academy_id, name)');
    }

    private function indexExists(string $table, string $index): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?',
            [$table, $index]
        );
    }
}
