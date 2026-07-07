<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260706000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create teams table for EP-005.';
    }

    public function up(Schema $schema): void
    {
        if ($this->tableExists('teams')) {
            return;
        }

        $this->addSql('CREATE TABLE teams (
            id CHAR(36) NOT NULL,
            academy_id CHAR(36) NOT NULL,
            category_id CHAR(36) NOT NULL,
            name VARCHAR(150) NOT NULL,
            status VARCHAR(20) NOT NULL,
            created_at DATETIME NOT NULL,
            created_by CHAR(36) DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            updated_by CHAR(36) DEFAULT NULL,
            deleted_at DATETIME DEFAULT NULL,
            deleted_by CHAR(36) DEFAULT NULL,
            INDEX IDX_TEAM_ACADEMY (academy_id),
            INDEX IDX_TEAM_CATEGORY (category_id),
            INDEX IDX_TEAM_STATUS (status),
            INDEX IDX_TEAM_CREATED_BY (created_by),
            INDEX IDX_TEAM_UPDATED_BY (updated_by),
            INDEX IDX_TEAM_DELETED_BY (deleted_by),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        if (!$this->tableExists('teams')) {
            return;
        }

        $this->addSql('DROP TABLE teams');
    }

    private function tableExists(string $table): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
            [$table]
        );
    }
}
