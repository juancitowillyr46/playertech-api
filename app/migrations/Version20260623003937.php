<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260623003937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if (!$this->tableExists('venues')) {
            $this->addSql('CREATE TABLE venues (academy_id CHAR(36) NOT NULL, deleted_at DATETIME DEFAULT NULL, deleted_by CHAR(36) DEFAULT NULL, id CHAR(36) NOT NULL, name VARCHAR(150) NOT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(120) DEFAULT NULL, phone VARCHAR(30) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, status VARCHAR(20) NOT NULL, created_by CHAR(36) DEFAULT NULL, updated_by CHAR(36) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE venues');
    }

    private function tableExists(string $table): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
            [$table]
        );
    }
}
