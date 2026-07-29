<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260727000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create player import jobs and messenger queue tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE player_import_jobs (
            id CHAR(36) NOT NULL,
            academy_id CHAR(36) NOT NULL,
            created_by CHAR(36) NOT NULL,
            category_id CHAR(36) NOT NULL,
            original_file_name VARCHAR(255) NOT NULL,
            file_path VARCHAR(500) NOT NULL,
            status VARCHAR(30) NOT NULL,
            progress INT NOT NULL,
            total_rows INT NOT NULL,
            processed_rows INT NOT NULL,
            success_rows INT NOT NULL,
            error_rows INT NOT NULL,
            errors JSON NOT NULL,
            started_at DATETIME DEFAULT NULL,
            finished_at DATETIME DEFAULT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            deleted_at DATETIME DEFAULT NULL,
            deleted_by CHAR(36) DEFAULT NULL,
            INDEX IDX_PLAYER_IMPORT_JOBS_ACADEMY_ID (academy_id),
            INDEX IDX_PLAYER_IMPORT_JOBS_CATEGORY_ID (category_id),
            INDEX IDX_PLAYER_IMPORT_JOBS_STATUS (status),
            INDEX IDX_PLAYER_IMPORT_JOBS_CREATED_BY (created_by),
            INDEX IDX_PLAYER_IMPORT_JOBS_DELETED_BY (deleted_by),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE messenger_messages (
            id BIGINT AUTO_INCREMENT NOT NULL,
            body LONGTEXT NOT NULL,
            headers LONGTEXT NOT NULL,
            queue_name VARCHAR(190) NOT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            INDEX IDX_FB6E7BF16A717070 (queue_name),
            INDEX IDX_FB6E7BF152EA1E1 (available_at),
            INDEX IDX_FB6E7BF156A7170 (delivered_at),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS player_import_jobs');
        $this->addSql('DROP TABLE IF EXISTS messenger_messages');
    }
}
