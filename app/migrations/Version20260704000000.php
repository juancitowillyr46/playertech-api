<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260704000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds shield to academy and removes old logo';
    }

    public function up(Schema $schema): void
    {
        $addShieldColumns = [];

        if (!$this->columnExists('academies', 'shield_path')) {
            $addShieldColumns[] = 'ADD shield_path VARCHAR(255) DEFAULT NULL';
        }

        if (!$this->columnExists('academies', 'shield_url')) {
            $addShieldColumns[] = 'ADD shield_url VARCHAR(255) DEFAULT NULL';
        }

        if (!$this->columnExists('academies', 'shield_mime_type')) {
            $addShieldColumns[] = 'ADD shield_mime_type VARCHAR(64) DEFAULT NULL';
        }

        if (!$this->columnExists('academies', 'shield_size')) {
            $addShieldColumns[] = 'ADD shield_size INT DEFAULT NULL';
        }

        if (!$this->columnExists('academies', 'shield_checksum')) {
            $addShieldColumns[] = 'ADD shield_checksum VARCHAR(255) DEFAULT NULL';
        }

        if ($this->columnExists('academies', 'logo')) {
            $addShieldColumns[] = 'DROP logo';
        }

        if ([] === $addShieldColumns) {
            return;
        }

        $this->addSql(sprintf(
            'ALTER TABLE academies %s',
            implode(', ', $addShieldColumns)
        ));
    }

    public function down(Schema $schema): void
    {
        $downParts = [];

        if (!$this->columnExists('academies', 'logo')) {
            $downParts[] = 'ADD logo VARCHAR(255) DEFAULT NULL';
        }

        foreach ([
            'shield_path',
            'shield_url',
            'shield_mime_type',
            'shield_size',
            'shield_checksum',
        ] as $column) {
            if ($this->columnExists('academies', $column)) {
                $downParts[] = sprintf('DROP %s', $column);
            }
        }

        if ([] === $downParts) {
            return;
        }

        $this->addSql(sprintf(
            'ALTER TABLE academies %s',
            implode(', ', $downParts)
        ));
    }

    private function columnExists(string $table, string $column): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$table, $column]
        );
    }
}
