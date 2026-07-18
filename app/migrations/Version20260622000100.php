<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260622000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add full_name to users for user CRUD';
    }

    public function up(Schema $schema): void
    {
        if ($this->columnExists('users', 'full_name')) {
            return;
        }

        $this->addSql('ALTER TABLE users ADD full_name VARCHAR(150) DEFAULT NULL AFTER academy_id');
    }

    public function down(Schema $schema): void
    {
        if ($this->columnExists('users', 'full_name')) {
            $this->addSql('ALTER TABLE users DROP full_name');
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
