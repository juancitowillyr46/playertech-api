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
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_VENUE_CREATED_BY ON venues (created_by)');
        $this->addSql('CREATE INDEX IDX_VENUE_UPDATED_BY ON venues (updated_by)');
        $this->addSql('CREATE INDEX IDX_VENUE_DELETED_BY ON venues (deleted_by)');
        $this->addSql('ALTER TABLE venues RENAME INDEX idx_category_academy TO IDX_VENUE_ACADEMY');
        $this->addSql('ALTER TABLE venues RENAME INDEX idx_category_status TO IDX_VENUE_STATUS');
        $this->addSql('ALTER TABLE venues RENAME INDEX uniq_category_academy_name TO UNIQ_VENUE_ACADEMY_NAME');
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
}
