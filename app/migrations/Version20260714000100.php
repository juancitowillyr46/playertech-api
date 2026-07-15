<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260714000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add tax check digit to academies for fiscal profile.';
    }

    public function up(Schema $schema): void
    {
        if (!$this->columnExists('academies', 'tax_check_digit')) {
            $this->addSql('ALTER TABLE academies ADD tax_check_digit VARCHAR(10) DEFAULT NULL AFTER tax_id_number');
        }
    }

    public function down(Schema $schema): void
    {
        if ($this->columnExists('academies', 'tax_check_digit')) {
            $this->addSql('ALTER TABLE academies DROP tax_check_digit');
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
