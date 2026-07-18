<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260626183745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if (!$this->tableExists('categories')) {
            $this->addSql('CREATE TABLE categories (academy_id CHAR(36) NOT NULL, deleted_at DATETIME DEFAULT NULL, deleted_by CHAR(36) DEFAULT NULL, id CHAR(36) NOT NULL, name VARCHAR(150) NOT NULL, min_age SMALLINT NOT NULL, max_age SMALLINT NOT NULL, description VARCHAR(150) NOT NULL, status VARCHAR(20) NOT NULL, created_by CHAR(36) DEFAULT NULL, updated_by CHAR(36) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_CATEGORY_ACADEMY (academy_id), INDEX IDX_CATEGORY_STATUS (status), UNIQUE INDEX UNIQ_CATEGORY_ACADEMY_NAME (academy_id, name), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP INDEX IDX_CATEGORY_ACADEMY ON categories');
        $this->addSql('DROP INDEX IDX_CATEGORY_STATUS ON categories');
        $this->addSql('DROP INDEX UNIQ_CATEGORY_ACADEMY_NAME ON categories');
    }

    private function tableExists(string $table): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
            [$table]
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
