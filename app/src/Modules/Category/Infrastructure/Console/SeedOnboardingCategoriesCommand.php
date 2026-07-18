<?php

declare(strict_types=1);

namespace App\Modules\Category\Infrastructure\Console;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:category:seed-onboarding',
    description: 'Seed or repair the public onboarding categories catalog.'
)]
final class SeedOnboardingCategoriesCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
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
            $this->entityManager->getConnection()->executeStatement(
                'INSERT INTO onboarding_categories (id, code, name, min_age, max_age, description, status, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NULL)
                 ON DUPLICATE KEY UPDATE
                    code = VALUES(code),
                    name = VALUES(name),
                    min_age = VALUES(min_age),
                    max_age = VALUES(max_age),
                    description = VALUES(description),
                    status = VALUES(status),
                    updated_at = VALUES(updated_at)',
                [$id, $code, $name, $minAge, $maxAge, $description, 'ACTIVE']
            );
        }

        $count = (int) $this->entityManager->getConnection()->fetchOne('SELECT COUNT(*) FROM onboarding_categories');

        $output->writeln(sprintf('<info>Onboarding catalog ready. Rows: %d</info>', $count));

        return self::SUCCESS;
    }
}
