<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260718000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add base player identity and sports profile fields.';
    }

    public function up(Schema $schema): void
    {
        if (!$this->columnExists('players', 'document_type')) {
            $this->addSql('ALTER TABLE players ADD document_type VARCHAR(50) NOT NULL AFTER academy_id');
        }

        if (!$this->columnExists('players', 'nationality')) {
            $this->addSql('ALTER TABLE players ADD nationality VARCHAR(100) DEFAULT NULL AFTER document_number');
        }

        if (!$this->columnExists('players', 'gender')) {
            $this->addSql('ALTER TABLE players ADD gender VARCHAR(20) DEFAULT NULL AFTER nationality');
        }

        if (!$this->columnExists('players', 'federation_id')) {
            $this->addSql('ALTER TABLE players ADD federation_id VARCHAR(80) DEFAULT NULL AFTER gender');
        }

        if (!$this->columnExists('players', 'dominant_foot')) {
            $this->addSql('ALTER TABLE players ADD dominant_foot VARCHAR(20) DEFAULT NULL AFTER federation_id');
        }
    }

    public function down(Schema $schema): void
    {
        $dropColumns = [];

        foreach ([
            'dominant_foot',
            'federation_id',
            'gender',
            'nationality',
            'document_type',
        ] as $column) {
            if ($this->columnExists('players', $column)) {
                $dropColumns[] = sprintf('DROP %s', $column);
            }
        }

        if ([] !== $dropColumns) {
            $this->addSql(sprintf('ALTER TABLE players %s', implode(', ', $dropColumns)));
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
