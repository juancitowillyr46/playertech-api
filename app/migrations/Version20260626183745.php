<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260626183745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (academy_id CHAR(36) NOT NULL, deleted_at DATETIME DEFAULT NULL, deleted_by CHAR(36) DEFAULT NULL, id CHAR(36) NOT NULL, name VARCHAR(150) NOT NULL, min_age SMALLINT NOT NULL, max_age SMALLINT NOT NULL, description VARCHAR(150) NOT NULL, status VARCHAR(20) NOT NULL, created_by CHAR(36) DEFAULT NULL, updated_by CHAR(36) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_CATEGORY_ACADEMY (academy_id), INDEX IDX_CATEGORY_STATUS (status), UNIQUE INDEX UNIQ_CATEGORY_ACADEMY_NAME (academy_id, name), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE INDEX IDX_CATEGORY_ACADEMY ON venues (academy_id)');
        $this->addSql('CREATE INDEX IDX_CATEGORY_STATUS ON venues (status)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CATEGORY_ACADEMY_NAME ON venues (academy_id, name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP INDEX IDX_CATEGORY_ACADEMY ON venues');
        $this->addSql('DROP INDEX IDX_CATEGORY_STATUS ON venues');
        $this->addSql('DROP INDEX UNIQ_CATEGORY_ACADEMY_NAME ON venues');
    }
}
