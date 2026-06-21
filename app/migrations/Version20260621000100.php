<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260621000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add soft delete columns to academies';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE academies ADD deleted_at DATETIME DEFAULT NULL, ADD deleted_by CHAR(36) DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_ACADEMIES_DELETED_BY ON academies (deleted_by)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IDX_ACADEMIES_DELETED_BY ON academies');
        $this->addSql('ALTER TABLE academies DROP deleted_at, DROP deleted_by');
    }
}
