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
    description: 'Create or update a tenant user for JWT authentication.'
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
            ->addOption('full-name', null, InputOption::VALUE_OPTIONAL, 'User full name')
            ->addOption('password', null, InputOption::VALUE_REQUIRED, 'User password')
            ->addOption('academy-id', null, InputOption::VALUE_REQUIRED, 'Academy UUID for tenant users')
            ->addOption('role', null, InputOption::VALUE_OPTIONAL, 'Primary role', AccountUser::DEFAULT_ROLE)
            ->addOption('status', null, InputOption::VALUE_OPTIONAL, 'User status', AccountUser::STATUS_ACTIVE);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = (string) $input->getOption('email');
        $fullName = (string) $input->getOption('full-name');
        $plainPassword = (string) $input->getOption('password');
        $academyId = (string) $input->getOption('academy-id');
        $role = (string) $input->getOption('role');
        $status = (string) $input->getOption('status');

        if ('' === $email || '' === $plainPassword || '' === $academyId) {
            $output->writeln('<error>Email, password and academy-id are required.</error>');

            return self::FAILURE;
        }

        if (!Uuid::isValid($academyId)) {
            $output->writeln('<error>academy-id must be a valid UUID.</error>');

            return self::FAILURE;
        }

        if (AccountUser::ROLE_ROOT === $role) {
            $output->writeln('<error>Use app:user:create-root for ROLE_ROOT users.</error>');

            return self::FAILURE;
        }

        if ('' === $fullName) {
            $fullName = $email;
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

        $user->setFullName($fullName);
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
