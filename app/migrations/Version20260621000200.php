<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260621000200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add activation token fields to users';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD activation_token VARCHAR(64) DEFAULT NULL, ADD activation_expires_at DATETIME DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_USERS_ACTIVATION_TOKEN ON users (activation_token)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_USERS_ACTIVATION_TOKEN ON users');
        $this->addSql('ALTER TABLE users DROP activation_token, DROP activation_expires_at');
    }
}
