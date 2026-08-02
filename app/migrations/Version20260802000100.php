<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260802000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Align legal_guardians table with the current Doctrine mapping.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE legal_guardians ADD document_type VARCHAR(50) DEFAULT NULL AFTER email');
        $this->addSql('ALTER TABLE legal_guardians ADD document_number VARCHAR(30) DEFAULT NULL AFTER document_type');
        $this->addSql('ALTER TABLE legal_guardians ADD address VARCHAR(255) DEFAULT NULL AFTER document_number');
        $this->addSql('ALTER TABLE legal_guardians ADD relationship VARCHAR(50) NOT NULL AFTER address');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE legal_guardians DROP COLUMN relationship');
        $this->addSql('ALTER TABLE legal_guardians DROP COLUMN address');
        $this->addSql('ALTER TABLE legal_guardians DROP COLUMN document_number');
        $this->addSql('ALTER TABLE legal_guardians DROP COLUMN document_type');
    }
}
