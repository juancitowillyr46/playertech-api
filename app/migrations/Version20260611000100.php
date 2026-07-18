<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260611000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Allow null academy_id for platform root users';
    }

    public function up(Schema $schema): void
    {
        if ($this->columnExists('users', 'academy_id')) {
            $this->addSql('ALTER TABLE users MODIFY academy_id CHAR(36) DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        if ($this->columnExists('users', 'academy_id')) {
            $this->addSql("UPDATE users SET academy_id = UUID() WHERE academy_id IS NULL");
            $this->addSql('ALTER TABLE users MODIFY academy_id CHAR(36) NOT NULL');
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
