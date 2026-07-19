<?php

declare(strict_types=1);

namespace App\Tests\Support\Database;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class TestDatabaseKernel extends KernelTestCase
{
    protected function bootTestKernel(): ContainerInterface
    {
        self::ensureKernelShutdown();
        self::bootKernel();

        return self::$kernel->getContainer();
    }

    protected function entityManager(ContainerInterface $container): EntityManagerInterface
    {
        return $container->get('doctrine')->getManager();
    }

    protected function jwtManager(ContainerInterface $container): object
    {
        return $container->get('lexik_jwt_authentication.jwt_manager');
    }

    protected function passwordHasher(): UserPasswordHasherInterface
    {
        return new class implements UserPasswordHasherInterface {
            public function hashPassword(object $user, string $plainPassword): string
            {
                return 'hashed-'.$plainPassword;
            }

            public function isPasswordValid(object $user, string $plainPassword): bool
            {
                return true;
            }

            public function needsRehash(object $user): bool
            {
                return false;
            }
        };
    }
}
