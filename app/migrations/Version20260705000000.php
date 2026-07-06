<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260705000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds player photo media columns';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE players ADD photo_path VARCHAR(255) DEFAULT NULL, ADD photo_url VARCHAR(255) DEFAULT NULL, ADD photo_mime_type VARCHAR(64) DEFAULT NULL, ADD photo_size INT DEFAULT NULL, ADD photo_checksum VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE players DROP photo_path, DROP photo_url, DROP photo_mime_type, DROP photo_size, DROP photo_checksum');
    }
}
