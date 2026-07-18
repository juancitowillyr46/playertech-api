<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260621000200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add activation token fields to users';
    }

    public function up(Schema $schema): void
    {
        if ($this->columnExists('users', 'activation_token') && $this->columnExists('users', 'activation_expires_at')) {
            return;
        }

        $this->addSql('ALTER TABLE users ADD activation_token VARCHAR(64) DEFAULT NULL, ADD activation_expires_at DATETIME DEFAULT NULL');

        if (!$this->indexExists('users', 'UNIQ_USERS_ACTIVATION_TOKEN')) {
            $this->addSql('CREATE UNIQUE INDEX UNIQ_USERS_ACTIVATION_TOKEN ON users (activation_token)');
        }
    }

    public function down(Schema $schema): void
    {
        if ($this->indexExists('users', 'UNIQ_USERS_ACTIVATION_TOKEN')) {
            $this->addSql('DROP INDEX UNIQ_USERS_ACTIVATION_TOKEN ON users');
        }

        if ($this->columnExists('users', 'activation_token') || $this->columnExists('users', 'activation_expires_at')) {
            $this->addSql('ALTER TABLE users DROP activation_token, DROP activation_expires_at');
        }
    }

    private function columnExists(string $table, string $column): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$table, $column]
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
