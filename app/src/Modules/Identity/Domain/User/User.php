<?php

namespace App\Modules\Identity\Domain\User;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private string $id;
    private ?string $academyId = null;
    private string $email;
    private string $password;
    private array $roles = [];
    private ?string $status = null;

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_ACADEMIC_ADMIN';

        return array_values(array_unique($roles));
    }

    public function eraseCredentials(): void
    {
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAcademyId(): ?string
    {
        return $this->academyId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }
}
