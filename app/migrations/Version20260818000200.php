<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260818000200 extends AbstractMigration
{
    public function isTransactional(): bool
    {
        return false;
    }

    public function getDescription(): string
    {
        return 'Evolve memberships table to store responsible guardian and enrollment category.';
    }

    public function up(Schema $schema): void
    {
        if (false === $this->tableExists('memberships')) {
            return;
        }

        $this->addSql('ALTER TABLE memberships CHANGE primary_guardian_id responsible_guardian_id CHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE memberships ADD category_id CHAR(36) DEFAULT NULL AFTER responsible_guardian_id');
        $this->addSql('CREATE INDEX IDX_MEMBERSHIP_CATEGORY ON memberships (category_id)');
        $this->addSql('DROP INDEX IDX_MEMBERSHIP_GUARDIAN ON memberships');
        $this->addSql('CREATE INDEX IDX_MEMBERSHIP_GUARDIAN ON memberships (responsible_guardian_id)');
    }

    public function down(Schema $schema): void
    {
        if (false === $this->tableExists('memberships')) {
            return;
        }

        $this->addSql('DROP INDEX IDX_MEMBERSHIP_CATEGORY ON memberships');
        $this->addSql('DROP INDEX IDX_MEMBERSHIP_GUARDIAN ON memberships');
        $this->addSql('ALTER TABLE memberships DROP COLUMN category_id');
        $this->addSql('ALTER TABLE memberships CHANGE responsible_guardian_id primary_guardian_id CHAR(36) NOT NULL');
    }

    private function tableExists(string $table): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
            [$table],
        );
    }
}
