<?php

namespace App\Modules\Identity\Infrastructure\Console;

use App\Modules\Identity\Domain\User\AccountUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(
    name: 'app:user:create',
    description: 'Create or update a user for JWT authentication.'
)]
final class CreateUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'User email')
            ->addOption('password', null, InputOption::VALUE_REQUIRED, 'User password')
            ->addOption('academy-id', null, InputOption::VALUE_OPTIONAL, 'Academy UUID for tenant users', null)
            ->addOption('role', null, InputOption::VALUE_OPTIONAL, 'Primary role', AccountUser::DEFAULT_ROLE)
            ->addOption('status', null, InputOption::VALUE_OPTIONAL, 'User status', AccountUser::STATUS_ACTIVE);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = (string) $input->getOption('email');
        $plainPassword = (string) $input->getOption('password');
        $academyId = $input->getOption('academy-id') ?: Uuid::v4()->toRfc4122();
        $role = (string) $input->getOption('role');
        $status = (string) $input->getOption('status');

        if ('' === $email || '' === $plainPassword) {
            $output->writeln('<error>Email and password are required.</error>');

            return self::FAILURE;
        }

        $repository = $this->entityManager->getRepository(AccountUser::class);
        /** @var AccountUser|null $user */
        $user = $repository->findOneBy(['email' => $email]);
        $isNewUser = !$user instanceof AccountUser;

        if ($isNewUser) {
            $user = new AccountUser();
            $user->setEmail($email);
            $this->entityManager->persist($user);
        }

        $user->setAcademyId($academyId);
        $user->setRole($role);
        $user->setStatus($status);
        $user->setPasswordHash($this->passwordHasher->hashPassword($user, $plainPassword));

        if (!$isNewUser) {
            $user->setUpdatedAt(new \DateTimeImmutable());
        }

        $this->entityManager->flush();

        $output->writeln(sprintf('<info>User "%s" ready.</info>', $email));
        $output->writeln(sprintf('<comment>Academy ID: %s</comment>', $academyId));
        $output->writeln(sprintf('<comment>Role: %s</comment>', $role));

        return self::SUCCESS;
    }
}

