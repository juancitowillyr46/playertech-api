<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260704000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds shield to academy and removes old logo';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE academies ADD shield_path VARCHAR(255) DEFAULT NULL, ADD shield_url VARCHAR(255) DEFAULT NULL, ADD shield_mime_type VARCHAR(64) DEFAULT NULL, ADD shield_size INT DEFAULT NULL, ADD shield_checksum VARCHAR(255) DEFAULT NULL, DROP logo');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE academies ADD logo VARCHAR(255) DEFAULT NULL, DROP shield_path, DROP shield_url, DROP shield_mime_type, DROP shield_size, DROP shield_checksum');
    }
}
