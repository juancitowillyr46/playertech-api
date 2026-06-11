<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260610000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create users table for JWT authentication';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('users')) {
            $table = $schema->getTable('users');

            if (!$table->hasIndex('UNIQ_1483A5E9E7927C74')) {
                $this->addSql('ALTER TABLE users ADD UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email)');
            }

            return;
        }

        $this->addSql('CREATE TABLE users (
            id CHAR(36) NOT NULL,
            academy_id CHAR(36) DEFAULT NULL,
            email VARCHAR(180) NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            role VARCHAR(50) NOT NULL,
            status VARCHAR(20) NOT NULL,
            created_at DATETIME NOT NULL,
            created_by CHAR(36) DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            updated_by CHAR(36) DEFAULT NULL,
            deleted_at DATETIME DEFAULT NULL,
            deleted_by CHAR(36) DEFAULT NULL,
            UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email),
            INDEX IDX_USERS_ACADEMY_ID (academy_id),
            INDEX IDX_USERS_CREATED_BY (created_by),
            INDEX IDX_USERS_UPDATED_BY (updated_by),
            INDEX IDX_USERS_DELETED_BY (deleted_by),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        if (!$schema->hasTable('users')) {
            return;
        }

        $this->addSql('DROP TABLE users');
    }
}
