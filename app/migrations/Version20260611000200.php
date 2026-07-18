<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260611000200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Convert users UUID columns from binary to string representation';
    }

    public function up(Schema $schema): void
    {
        if ($this->isStringUuidUsersTable()) {
            return;
        }

        $this->addSql('CREATE TABLE users_v2 (
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

        $this->addSql('INSERT INTO users_v2 (id, academy_id, email, password_hash, role, status, created_at, created_by, updated_at, updated_by, deleted_at, deleted_by)
            SELECT BIN_TO_UUID(id), BIN_TO_UUID(academy_id), email, password_hash, role, status, created_at, BIN_TO_UUID(created_by), updated_at, BIN_TO_UUID(updated_by), deleted_at, BIN_TO_UUID(deleted_by)
            FROM users');

        $this->addSql('DROP TABLE users');
        $this->addSql('RENAME TABLE users_v2 TO users');
    }

    public function down(Schema $schema): void
    {
        if ($this->isStringUuidUsersTable()) {
            return;
        }

        $this->addSql('CREATE TABLE users_v2 (
            id BINARY(16) NOT NULL,
            academy_id BINARY(16) DEFAULT NULL,
            email VARCHAR(180) NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            role VARCHAR(50) NOT NULL,
            status VARCHAR(20) NOT NULL,
            created_at DATETIME NOT NULL,
            created_by BINARY(16) DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            updated_by BINARY(16) DEFAULT NULL,
            deleted_at DATETIME DEFAULT NULL,
            deleted_by BINARY(16) DEFAULT NULL,
            UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email),
            INDEX IDX_USERS_ACADEMY_ID (academy_id),
            INDEX IDX_USERS_CREATED_BY (created_by),
            INDEX IDX_USERS_UPDATED_BY (updated_by),
            INDEX IDX_USERS_DELETED_BY (deleted_by),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('INSERT INTO users_v2 (id, academy_id, email, password_hash, role, status, created_at, created_by, updated_at, updated_by, deleted_at, deleted_by)
            SELECT UUID_TO_BIN(id), UUID_TO_BIN(academy_id), email, password_hash, role, status, created_at, UUID_TO_BIN(created_by), updated_at, UUID_TO_BIN(updated_by), deleted_at, UUID_TO_BIN(deleted_by)
            FROM users');

        $this->addSql('DROP TABLE users');
        $this->addSql('RENAME TABLE users_v2 TO users');
    }

    private function isStringUuidUsersTable(): bool
    {
        $idType = $this->connection->fetchOne(
            'SELECT DATA_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            ['users', 'id']
        );

        return 'char' === strtolower((string) $idType);
    }
}
