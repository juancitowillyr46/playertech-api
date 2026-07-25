<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260725000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Allow category description to be nullable to match the API contract.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE categories MODIFY description VARCHAR(150) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE categories MODIFY description VARCHAR(150) NOT NULL');
    }
}
