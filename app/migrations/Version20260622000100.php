<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260622000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add full_name to users for user CRUD';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD full_name VARCHAR(150) DEFAULT NULL AFTER academy_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users DROP full_name');
    }
}
