<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260718000200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add optional contact fields to players.';
    }

    public function up(Schema $schema): void
    {
        if (!$this->columnExists('players', 'email')) {
            $this->addSql('ALTER TABLE players ADD email VARCHAR(255) DEFAULT NULL AFTER document_number');
        }

        if (!$this->columnExists('players', 'phone')) {
            $this->addSql('ALTER TABLE players ADD phone VARCHAR(50) DEFAULT NULL AFTER email');
        }
    }

    public function down(Schema $schema): void
    {
        if ($this->columnExists('players', 'phone')) {
            $this->addSql('ALTER TABLE players DROP phone');
        }

        if ($this->columnExists('players', 'email')) {
            $this->addSql('ALTER TABLE players DROP email');
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
