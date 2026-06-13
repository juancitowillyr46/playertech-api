<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260613000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create academies table for EP-001 foundation';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('academies')) {
            return;
        }

        $this->addSql('CREATE TABLE academies (
            id CHAR(36) NOT NULL,
            name VARCHAR(150) NOT NULL,
            contact_email VARCHAR(180) NOT NULL,
            phone VARCHAR(30) DEFAULT NULL,
            address VARCHAR(255) DEFAULT NULL,
            city VARCHAR(120) DEFAULT NULL,
            logo VARCHAR(255) DEFAULT NULL,
            status VARCHAR(20) NOT NULL,
            created_at DATETIME NOT NULL,
            created_by CHAR(36) DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            updated_by CHAR(36) DEFAULT NULL,
            UNIQUE INDEX UNIQ_ACADEMIES_CONTACT_EMAIL (contact_email),
            INDEX IDX_ACADEMIES_STATUS (status),
            INDEX IDX_ACADEMIES_CREATED_BY (created_by),
            INDEX IDX_ACADEMIES_UPDATED_BY (updated_by),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        if (!$schema->hasTable('academies')) {
            return;
        }

        $this->addSql('DROP TABLE academies');
    }
}
