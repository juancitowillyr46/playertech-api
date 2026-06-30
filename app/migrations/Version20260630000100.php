<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260630000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add category business key for category API and bulk import.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE categories ADD category_key VARCHAR(50) DEFAULT NULL');
        $this->addSql("UPDATE categories SET category_key = LEFT(CONCAT(UPPER(REPLACE(REPLACE(name, ' ', '_'), '-', '_')), '_', REPLACE(LEFT(id, 8), '-', '')), 50)");
        $this->addSql('ALTER TABLE categories MODIFY category_key VARCHAR(50) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CATEGORY_ACADEMY_KEY ON categories (academy_id, category_key)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_CATEGORY_ACADEMY_KEY ON categories');
        $this->addSql('ALTER TABLE categories DROP category_key');
    }
}
