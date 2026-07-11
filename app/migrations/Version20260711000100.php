<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260711000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Track academy registration source for signup and platform-created tenants.';
    }

    public function up(Schema $schema): void
    {
        if (!$this->columnExists('academies', 'registration_source')) {
            $this->addSql("ALTER TABLE academies ADD registration_source VARCHAR(20) NOT NULL DEFAULT 'signup' AFTER department");
        }
    }

    public function down(Schema $schema): void
    {
        if ($this->columnExists('academies', 'registration_source')) {
            $this->addSql('ALTER TABLE academies DROP registration_source');
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
