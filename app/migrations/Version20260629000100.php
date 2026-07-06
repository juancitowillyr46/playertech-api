<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260629000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create players table for EP-007 HU-001.';
    }

    public function up(Schema $schema): void
    {
        if (!$this->tableExists('players')) {
            $this->addSql('CREATE TABLE players (id CHAR(36) NOT NULL, academy_id CHAR(36) NOT NULL, category_id CHAR(36) DEFAULT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, birth_date DATE NOT NULL, document_number VARCHAR(30) NOT NULL, status VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, created_by CHAR(36) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, updated_by CHAR(36) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, deleted_by CHAR(36) DEFAULT NULL, INDEX IDX_PLAYER_ACADEMY (academy_id), INDEX IDX_PLAYER_CATEGORY (category_id), INDEX IDX_PLAYER_STATUS (status), INDEX IDX_PLAYER_CREATED_BY (created_by), INDEX IDX_PLAYER_UPDATED_BY (updated_by), INDEX IDX_PLAYER_DELETED_BY (deleted_by), UNIQUE INDEX UNIQ_PLAYER_ACADEMY_DOCUMENT_NUMBER (academy_id, document_number), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE players');
    }

    private function tableExists(string $table): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
            [$table]
        );
    }
}
