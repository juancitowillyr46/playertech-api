<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260718000300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create onboarding categories catalog for public tenant signup.';
    }

    public function up(Schema $schema): void
    {
        if (!$this->tableExists('onboarding_categories')) {
            $this->addSql('CREATE TABLE onboarding_categories (
                id CHAR(36) NOT NULL,
                code VARCHAR(50) NOT NULL,
                name VARCHAR(100) NOT NULL,
                min_age INT NOT NULL,
                max_age INT NOT NULL,
                description LONGTEXT DEFAULT NULL,
                status VARCHAR(20) NOT NULL,
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                INDEX IDX_ONBOARDING_CATEGORY_STATUS (status),
                UNIQUE INDEX UNIQ_ONBOARDING_CATEGORY_CODE (code),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        $catalog = [];

        for ($age = 4; $age <= 20; ++$age) {
            $catalog[] = [
                sprintf('019f7000-0000-7000-8000-000000000%03d', $age - 3),
                sprintf('SUB-%02d', $age),
                sprintf('Sub %d', $age),
                $age - 1,
                $age,
                'Categoria formativa',
            ];
        }

        foreach ($catalog as [$id, $code, $name, $minAge, $maxAge, $description]) {
            if ($this->rowExists('onboarding_categories', 'id', $id) || $this->rowExists('onboarding_categories', 'code', $code)) {
                continue;
            }

            $this->addSql(
                'INSERT INTO onboarding_categories (id, code, name, min_age, max_age, description, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NULL)',
                [$id, $code, $name, $minAge, $maxAge, $description, 'ACTIVE']
            );
        }
    }

    public function down(Schema $schema): void
    {
        if ($this->tableExists('onboarding_categories')) {
            $this->addSql('DROP TABLE onboarding_categories');
        }
    }

    private function tableExists(string $table): bool
    {
        return false !== $this->connection->fetchOne(
            'SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
            [$table]
        );
    }

    private function rowExists(string $table, string $column, string $value): bool
    {
        return false !== $this->connection->fetchOne(
            sprintf('SELECT 1 FROM %s WHERE %s = ? LIMIT 1', $table, $column),
            [$value]
        );
    }
}
