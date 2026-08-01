<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260730000100 extends AbstractMigration
{
    public function getDescription(): string { return 'Create private player document metadata storage.'; }
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE player_documents (
            id CHAR(36) NOT NULL,
            academy_id CHAR(36) COLLATE utf8mb4_unicode_ci NOT NULL,
            player_id CHAR(36) COLLATE utf8mb4_0900_ai_ci NOT NULL,
            document_type VARCHAR(20) NOT NULL,
            original_file_name VARCHAR(255) NOT NULL,
            storage_name VARCHAR(255) NOT NULL,
            mime_type VARCHAR(100) NOT NULL,
            file_size INT NOT NULL,
            file_extension VARCHAR(10) NOT NULL,
            observations LONGTEXT DEFAULT NULL,
            created_at DATETIME NOT NULL,
            created_by CHAR(36) DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            updated_by CHAR(36) DEFAULT NULL,
            deleted_at DATETIME DEFAULT NULL,
            deleted_by CHAR(36) DEFAULT NULL,
            UNIQUE INDEX UNIQ_PLAYER_DOCUMENT_STORAGE (storage_name),
            INDEX IDX_PLAYER_DOCUMENT_PLAYER_ACTIVE (academy_id, player_id, deleted_at),
            INDEX IDX_PLAYER_DOCUMENT_CREATED_BY (created_by),
            INDEX IDX_PLAYER_DOCUMENT_UPDATED_BY (updated_by),
            INDEX IDX_PLAYER_DOCUMENT_DELETED_BY (deleted_by),
            PRIMARY KEY(id),
            CONSTRAINT FK_PLAYER_DOCUMENT_ACADEMY FOREIGN KEY (academy_id) REFERENCES academies (id),
            CONSTRAINT FK_PLAYER_DOCUMENT_PLAYER FOREIGN KEY (player_id) REFERENCES players (id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }
    public function down(Schema $schema): void { $this->addSql('DROP TABLE IF EXISTS player_documents'); }
}
