<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260627193502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_CATEGORY_ACADEMY_NAME ON categories');
        $this->addSql('CREATE INDEX IDX_CATEGORY_CREATED_BY ON categories (created_by)');
        $this->addSql('CREATE INDEX IDX_CATEGORY_UPDATED_BY ON categories (updated_by)');
        $this->addSql('CREATE INDEX IDX_CATEGORY_DELETED_BY ON categories (deleted_by)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_CATEGORY_CREATED_BY ON categories');
        $this->addSql('DROP INDEX IDX_CATEGORY_UPDATED_BY ON categories');
        $this->addSql('DROP INDEX IDX_CATEGORY_DELETED_BY ON categories');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CATEGORY_ACADEMY_NAME ON categories (academy_id, name)');
    }
}
