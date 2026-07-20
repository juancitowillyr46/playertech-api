<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260720000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add unique constraint for academy phone to prevent duplicate signup and profile collisions.';
    }

    public function up(Schema $schema): void
    {
        if (!$this->tableExists('academies')) {
            return;
        }

        if (!$this->indexExists('academies', 'UNIQ_ACADEMIES_PHONE')) {
            $this->addSql('ALTER TABLE academies ADD UNIQUE INDEX UNIQ_ACADEMIES_PHONE (phone)');
        }
    }

    public function down(Schema $schema): void
    {
        if (!$this->tableExists('academies')) {
            return;
        }

        if ($this->indexExists('academies', 'UNIQ_ACADEMIES_PHONE')) {
            $this->addSql('ALTER TABLE academies DROP INDEX UNIQ_ACADEMIES_PHONE');
        }
    }

    private function tableExists(string $table): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
            [$table]
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
