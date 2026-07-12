<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260712000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add password reset tokens to users for recovery flow.';
    }

    public function up(Schema $schema): void
    {
        if (!$this->columnExists('users', 'password_reset_token')) {
            $this->addSql('ALTER TABLE users ADD password_reset_token VARCHAR(64) DEFAULT NULL AFTER activation_expires_at');
        }

        if (!$this->columnExists('users', 'password_reset_expires_at')) {
            $this->addSql('ALTER TABLE users ADD password_reset_expires_at DATETIME DEFAULT NULL AFTER password_reset_token');
        }
    }

    public function down(Schema $schema): void
    {
        if ($this->columnExists('users', 'password_reset_expires_at')) {
            $this->addSql('ALTER TABLE users DROP password_reset_expires_at');
        }

        if ($this->columnExists('users', 'password_reset_token')) {
            $this->addSql('ALTER TABLE users DROP password_reset_token');
        }
    }

    private function columnExists(string $table, string $column): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$table, $column]
        );
    }
}
