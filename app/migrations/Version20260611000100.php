<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260611000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Allow null academy_id for platform root users';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users MODIFY academy_id CHAR(36) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql("UPDATE users SET academy_id = UUID() WHERE academy_id IS NULL");
        $this->addSql('ALTER TABLE users MODIFY academy_id CHAR(36) NOT NULL');
    }
}
