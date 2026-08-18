<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260818000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create memberships table for the administrative membership lifecycle.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE memberships (
            academy_id CHAR(36) NOT NULL,
            player_id CHAR(36) NOT NULL,
            primary_guardian_id CHAR(36) NOT NULL,
            started_at DATETIME NOT NULL,
            ended_at DATETIME DEFAULT NULL,
            deleted_at DATETIME DEFAULT NULL,
            deleted_by CHAR(36) DEFAULT NULL,
            id CHAR(36) NOT NULL,
            status VARCHAR(20) NOT NULL,
            created_by CHAR(36) DEFAULT NULL,
            updated_by CHAR(36) DEFAULT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            INDEX IDX_MEMBERSHIP_ACADEMY (academy_id),
            INDEX IDX_MEMBERSHIP_PLAYER (player_id),
            INDEX IDX_MEMBERSHIP_GUARDIAN (primary_guardian_id),
            INDEX IDX_MEMBERSHIP_STATUS (status),
            UNIQUE INDEX UNIQ_MEMBERSHIP_ACADEMY_PLAYER_ACTIVE (academy_id, player_id, status),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        if (!$this->tableExists('memberships')) {
            return;
        }

        $this->addSql('DROP TABLE memberships');
    }

    private function tableExists(string $table): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
            [$table],
        );
    }
}
