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

#[AsCommand(
    name: 'app:user:create-root',
    description: 'Create or update a platform root user without tenant academy.'
)]
final class CreateRootUserCommand extends Command
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
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Root user email')
            ->addOption('full-name', null, InputOption::VALUE_OPTIONAL, 'Root user full name')
            ->addOption('password', null, InputOption::VALUE_REQUIRED, 'Root user password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = (string) $input->getOption('email');
        $fullName = (string) $input->getOption('full-name');
        $plainPassword = (string) $input->getOption('password');

        if ('' === $email || '' === $plainPassword) {
            $output->writeln('<error>Email and password are required.</error>');

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
        $user->setAcademyId(null);
        $user->setRole(AccountUser::ROLE_ROOT);
        $user->setStatus(AccountUser::STATUS_ACTIVE);
        $user->setPasswordHash($this->passwordHasher->hashPassword($user, $plainPassword));

        if (!$isNewUser) {
            $user->setUpdatedAt(new \DateTimeImmutable());
        }

        $this->entityManager->flush();

        $output->writeln(sprintf('<info>Root user "%s" ready.</info>', $email));
        $output->writeln('<comment>Academy ID: null</comment>');
        $output->writeln('<comment>Role: ROLE_ROOT</comment>');

        return self::SUCCESS;
    }
}
