<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260707000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create legal_guardians and player_guardians tables for EP-008.';
    }

    public function up(Schema $schema): void
    {
        if (!$this->tableExists('legal_guardians')) {
            $this->addSql('CREATE TABLE legal_guardians (
                id CHAR(36) NOT NULL,
                academy_id CHAR(36) NOT NULL,
                first_name VARCHAR(100) NOT NULL,
                last_name VARCHAR(100) NOT NULL,
                phone VARCHAR(30) DEFAULT NULL,
                email VARCHAR(255) DEFAULT NULL,
                status VARCHAR(20) NOT NULL,
                created_at DATETIME NOT NULL,
                created_by CHAR(36) DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                updated_by CHAR(36) DEFAULT NULL,
                deleted_at DATETIME DEFAULT NULL,
                deleted_by CHAR(36) DEFAULT NULL,
                UNIQUE INDEX UNIQ_LEGAL_GUARDIAN_ACADEMY_EMAIL (academy_id, email),
                INDEX IDX_LEGAL_GUARDIAN_ACADEMY (academy_id),
                INDEX IDX_LEGAL_GUARDIAN_STATUS (status),
                INDEX IDX_LEGAL_GUARDIAN_CREATED_BY (created_by),
                INDEX IDX_LEGAL_GUARDIAN_UPDATED_BY (updated_by),
                INDEX IDX_LEGAL_GUARDIAN_DELETED_BY (deleted_by),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        if (!$this->tableExists('player_guardians')) {
            $this->addSql('CREATE TABLE player_guardians (
                id CHAR(36) NOT NULL,
                academy_id CHAR(36) NOT NULL,
                player_id CHAR(36) NOT NULL,
                guardian_id CHAR(36) NOT NULL,
                is_primary TINYINT(1) NOT NULL DEFAULT 0,
                created_at DATETIME NOT NULL,
                created_by CHAR(36) DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                updated_by CHAR(36) DEFAULT NULL,
                deleted_at DATETIME DEFAULT NULL,
                deleted_by CHAR(36) DEFAULT NULL,
                UNIQUE INDEX UNIQ_PLAYER_GUARDIAN_LINK (academy_id, player_id, guardian_id),
                INDEX IDX_PLAYER_GUARDIAN_ACADEMY (academy_id),
                INDEX IDX_PLAYER_GUARDIAN_PLAYER (player_id),
                INDEX IDX_PLAYER_GUARDIAN_GUARDIAN (guardian_id),
                INDEX IDX_PLAYER_GUARDIAN_PRIMARY (is_primary),
                INDEX IDX_PLAYER_GUARDIAN_CREATED_BY (created_by),
                INDEX IDX_PLAYER_GUARDIAN_UPDATED_BY (updated_by),
                INDEX IDX_PLAYER_GUARDIAN_DELETED_BY (deleted_by),
                PRIMARY KEY(id),
                CONSTRAINT FK_PLAYER_GUARDIAN_ACADEMY FOREIGN KEY (academy_id) REFERENCES academies (id),
                CONSTRAINT FK_PLAYER_GUARDIAN_PLAYER FOREIGN KEY (player_id) REFERENCES players (id),
                CONSTRAINT FK_PLAYER_GUARDIAN_GUARDIAN FOREIGN KEY (guardian_id) REFERENCES legal_guardians (id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }
    }

    public function down(Schema $schema): void
    {
        if ($this->tableExists('player_guardians')) {
            $this->addSql('DROP TABLE player_guardians');
        }

        if ($this->tableExists('legal_guardians')) {
            $this->addSql('DROP TABLE legal_guardians');
        }
    }

    private function tableExists(string $table): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
            [$table]
        );
    }
}
