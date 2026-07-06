<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260626213608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if (!$this->indexExists('venues', 'IDX_VENUE_CREATED_BY')) {
            $this->addSql('CREATE INDEX IDX_VENUE_CREATED_BY ON venues (created_by)');
        }

        if (!$this->indexExists('venues', 'IDX_VENUE_UPDATED_BY')) {
            $this->addSql('CREATE INDEX IDX_VENUE_UPDATED_BY ON venues (updated_by)');
        }

        if (!$this->indexExists('venues', 'IDX_VENUE_DELETED_BY')) {
            $this->addSql('CREATE INDEX IDX_VENUE_DELETED_BY ON venues (deleted_by)');
        }

        if ($this->indexExists('venues', 'idx_category_academy') && !$this->indexExists('venues', 'IDX_VENUE_ACADEMY')) {
            $this->addSql('ALTER TABLE venues RENAME INDEX idx_category_academy TO IDX_VENUE_ACADEMY');
        }

        if ($this->indexExists('venues', 'idx_category_status') && !$this->indexExists('venues', 'IDX_VENUE_STATUS')) {
            $this->addSql('ALTER TABLE venues RENAME INDEX idx_category_status TO IDX_VENUE_STATUS');
        }

        if ($this->indexExists('venues', 'uniq_category_academy_name') && !$this->indexExists('venues', 'UNIQ_VENUE_ACADEMY_NAME')) {
            $this->addSql('ALTER TABLE venues RENAME INDEX uniq_category_academy_name TO UNIQ_VENUE_ACADEMY_NAME');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_VENUE_CREATED_BY ON venues');
        $this->addSql('DROP INDEX IDX_VENUE_UPDATED_BY ON venues');
        $this->addSql('DROP INDEX IDX_VENUE_DELETED_BY ON venues');
        $this->addSql('ALTER TABLE venues RENAME INDEX idx_venue_academy TO IDX_CATEGORY_ACADEMY');
        $this->addSql('ALTER TABLE venues RENAME INDEX idx_venue_status TO IDX_CATEGORY_STATUS');
        $this->addSql('ALTER TABLE venues RENAME INDEX uniq_venue_academy_name TO UNIQ_CATEGORY_ACADEMY_NAME');
    }

    private function indexExists(string $table, string $index): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?',
            [$table, $index]
        );
    }
}
