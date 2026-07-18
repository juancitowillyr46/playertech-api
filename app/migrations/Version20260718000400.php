<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260718000400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create missing operational tables for charges, staff, assignments and payments.';
    }

    public function up(Schema $schema): void
    {
        if (!$this->tableExists('charges')) {
            $this->addSql('CREATE TABLE charges (
                id CHAR(36) NOT NULL,
                academy_id CHAR(36) NOT NULL,
                player_id CHAR(36) NOT NULL,
                membership_id CHAR(36) NOT NULL,
                payment_concept_id CHAR(36) NOT NULL,
                description VARCHAR(255) NOT NULL,
                amount DECIMAL(12, 2) NOT NULL,
                allocated_amount DECIMAL(12, 2) NOT NULL,
                due_date DATE NOT NULL,
                source VARCHAR(20) NOT NULL,
                status VARCHAR(20) NOT NULL,
                created_at DATETIME NOT NULL,
                created_by CHAR(36) DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                updated_by CHAR(36) DEFAULT NULL,
                deleted_at DATETIME DEFAULT NULL,
                deleted_by CHAR(36) DEFAULT NULL,
                INDEX IDX_CHARGE_ACADEMY (academy_id),
                INDEX IDX_CHARGE_STATUS (status),
                INDEX IDX_CHARGE_CREATED_BY (created_by),
                INDEX IDX_CHARGE_UPDATED_BY (updated_by),
                INDEX IDX_CHARGE_DELETED_BY (deleted_by),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        if (!$this->tableExists('staff')) {
            $this->addSql('CREATE TABLE staff (
                id CHAR(36) NOT NULL,
                academy_id CHAR(36) NOT NULL,
                user_id CHAR(36) NOT NULL,
                status VARCHAR(20) NOT NULL,
                created_at DATETIME NOT NULL,
                created_by CHAR(36) DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                updated_by CHAR(36) DEFAULT NULL,
                deleted_at DATETIME DEFAULT NULL,
                deleted_by CHAR(36) DEFAULT NULL,
                UNIQUE INDEX UNIQ_STAFF_ACADEMY_USER (academy_id, user_id),
                INDEX IDX_STAFF_ACADEMY (academy_id),
                INDEX IDX_STAFF_USER (user_id),
                INDEX IDX_STAFF_CREATED_BY (created_by),
                INDEX IDX_STAFF_UPDATED_BY (updated_by),
                INDEX IDX_STAFF_DELETED_BY (deleted_by),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        if (!$this->tableExists('team_staff_assignments')) {
            $this->addSql('CREATE TABLE team_staff_assignments (
                id CHAR(36) NOT NULL,
                academy_id CHAR(36) NOT NULL,
                staff_id CHAR(36) NOT NULL,
                team_id CHAR(36) NOT NULL,
                role VARCHAR(50) NOT NULL,
                created_at DATETIME NOT NULL,
                created_by CHAR(36) DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                updated_by CHAR(36) DEFAULT NULL,
                deleted_at DATETIME DEFAULT NULL,
                deleted_by CHAR(36) DEFAULT NULL,
                UNIQUE INDEX UNIQ_TEAM_STAFF_ACADEMY_TEAM_STAFF (academy_id, team_id, staff_id),
                INDEX IDX_TEAM_STAFF_ACADEMY (academy_id),
                INDEX IDX_TEAM_STAFF_TEAM (team_id),
                INDEX IDX_TEAM_STAFF_STAFF (staff_id),
                INDEX IDX_TEAM_STAFF_CREATED_BY (created_by),
                INDEX IDX_TEAM_STAFF_UPDATED_BY (updated_by),
                INDEX IDX_TEAM_STAFF_DELETED_BY (deleted_by),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        if (!$this->tableExists('team_assignments')) {
            $this->addSql('CREATE TABLE team_assignments (
                id CHAR(36) NOT NULL,
                academy_id CHAR(36) NOT NULL,
                player_id CHAR(36) NOT NULL,
                team_id CHAR(36) NOT NULL,
                start_date DATE NOT NULL,
                end_date DATE DEFAULT NULL,
                is_primary TINYINT(1) NOT NULL,
                created_at DATETIME NOT NULL,
                created_by CHAR(36) DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                updated_by CHAR(36) DEFAULT NULL,
                deleted_at DATETIME DEFAULT NULL,
                deleted_by CHAR(36) DEFAULT NULL,
                UNIQUE INDEX UNIQ_TEAM_ASSIGNMENT_ACADEMY_PLAYER_TEAM (academy_id, player_id, team_id),
                INDEX IDX_TEAM_ASSIGNMENT_ACADEMY (academy_id),
                INDEX IDX_TEAM_ASSIGNMENT_PLAYER (player_id),
                INDEX IDX_TEAM_ASSIGNMENT_TEAM (team_id),
                INDEX IDX_TEAM_ASSIGNMENT_PRIMARY (is_primary),
                INDEX IDX_TEAM_ASSIGNMENT_CREATED_BY (created_by),
                INDEX IDX_TEAM_ASSIGNMENT_UPDATED_BY (updated_by),
                INDEX IDX_TEAM_ASSIGNMENT_DELETED_BY (deleted_by),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        if (!$this->tableExists('payments')) {
            $this->addSql('CREATE TABLE payments (
                id CHAR(36) NOT NULL,
                academy_id CHAR(36) NOT NULL,
                membership_id CHAR(36) NOT NULL,
                player_id CHAR(36) NOT NULL,
                guardian_id CHAR(36) NOT NULL,
                payment_concept_id CHAR(36) NOT NULL,
                payment_date DATE NOT NULL,
                amount DECIMAL(12, 2) NOT NULL,
                method VARCHAR(30) NOT NULL,
                notes LONGTEXT DEFAULT NULL,
                status VARCHAR(20) NOT NULL,
                created_at DATETIME NOT NULL,
                created_by CHAR(36) DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                updated_by CHAR(36) DEFAULT NULL,
                deleted_at DATETIME DEFAULT NULL,
                deleted_by CHAR(36) DEFAULT NULL,
                INDEX IDX_PAYMENT_ACADEMY (academy_id),
                INDEX IDX_PAYMENT_STATUS (status),
                INDEX IDX_PAYMENT_CREATED_BY (created_by),
                INDEX IDX_PAYMENT_UPDATED_BY (updated_by),
                INDEX IDX_PAYMENT_DELETED_BY (deleted_by),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        if (!$this->tableExists('payment_allocations')) {
            $this->addSql('CREATE TABLE payment_allocations (
                id CHAR(36) NOT NULL,
                academy_id CHAR(36) NOT NULL,
                payment_id CHAR(36) NOT NULL,
                charge_id CHAR(36) NOT NULL,
                amount DECIMAL(12, 2) NOT NULL,
                created_at DATETIME NOT NULL,
                created_by CHAR(36) DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                updated_by CHAR(36) DEFAULT NULL,
                deleted_at DATETIME DEFAULT NULL,
                deleted_by CHAR(36) DEFAULT NULL,
                INDEX IDX_PAYMENT_ALLOCATION_ACADEMY (academy_id),
                INDEX IDX_PAYMENT_ALLOCATION_PAYMENT (payment_id),
                INDEX IDX_PAYMENT_ALLOCATION_CHARGE (charge_id),
                INDEX IDX_PAYMENT_ALLOCATION_CREATED_BY (created_by),
                INDEX IDX_PAYMENT_ALLOCATION_UPDATED_BY (updated_by),
                INDEX IDX_PAYMENT_ALLOCATION_DELETED_BY (deleted_by),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        if (!$this->tableExists('payment_evidences')) {
            $this->addSql('CREATE TABLE payment_evidences (
                id CHAR(36) NOT NULL,
                academy_id CHAR(36) NOT NULL,
                payment_id CHAR(36) NOT NULL,
                file_name VARCHAR(255) NOT NULL,
                file_path VARCHAR(500) NOT NULL,
                mime_type VARCHAR(100) NOT NULL,
                created_at DATETIME NOT NULL,
                created_by CHAR(36) DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                updated_by CHAR(36) DEFAULT NULL,
                deleted_at DATETIME DEFAULT NULL,
                deleted_by CHAR(36) DEFAULT NULL,
                INDEX IDX_PAYMENT_EVIDENCE_ACADEMY (academy_id),
                INDEX IDX_PAYMENT_EVIDENCE_PAYMENT (payment_id),
                INDEX IDX_PAYMENT_EVIDENCE_CREATED_BY (created_by),
                INDEX IDX_PAYMENT_EVIDENCE_UPDATED_BY (updated_by),
                INDEX IDX_PAYMENT_EVIDENCE_DELETED_BY (deleted_by),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        if (!$this->tableExists('fiscal_attachments')) {
            $this->addSql('CREATE TABLE fiscal_attachments (
                id CHAR(36) NOT NULL,
                academy_id CHAR(36) NOT NULL,
                payment_id CHAR(36) NOT NULL,
                provider_name VARCHAR(120) NOT NULL,
                document_number VARCHAR(120) NOT NULL,
                document_url VARCHAR(500) DEFAULT NULL,
                status VARCHAR(30) DEFAULT NULL,
                created_at DATETIME NOT NULL,
                created_by CHAR(36) DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                updated_by CHAR(36) DEFAULT NULL,
                deleted_at DATETIME DEFAULT NULL,
                deleted_by CHAR(36) DEFAULT NULL,
                INDEX IDX_FISCAL_ATTACHMENT_ACADEMY (academy_id),
                INDEX IDX_FISCAL_ATTACHMENT_PAYMENT (payment_id),
                INDEX IDX_FISCAL_ATTACHMENT_CREATED_BY (created_by),
                INDEX IDX_FISCAL_ATTACHMENT_UPDATED_BY (updated_by),
                INDEX IDX_FISCAL_ATTACHMENT_DELETED_BY (deleted_by),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }
    }

    public function down(Schema $schema): void
    {
        foreach ([
            'fiscal_attachments',
            'payment_evidences',
            'payment_allocations',
            'payments',
            'team_assignments',
            'team_staff_assignments',
            'staff',
            'charges',
        ] as $table) {
            if ($this->tableExists($table)) {
                $this->addSql(sprintf('DROP TABLE %s', $table));
            }
        }
    }

    private function tableExists(string $table): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
            [$table]
        );
    }
}
