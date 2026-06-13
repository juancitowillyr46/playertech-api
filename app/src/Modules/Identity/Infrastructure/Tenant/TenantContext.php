<?php

declare(strict_types=1);

namespace App\Modules\Identity\Infrastructure\Tenant;

use App\Modules\Identity\Domain\User\AccountUser;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class TenantContext
{
    public const MODE_ANONYMOUS = 'anonymous';
    public const MODE_PLATFORM = 'platform';
    public const MODE_TENANT = 'tenant';

    public function __construct(private readonly TokenStorageInterface $tokenStorage)
    {
    }

    public function getMode(): string
    {
        $context = $this->getContext();

        if ([] !== $context && isset($context['mode'])) {
            return (string) $context['mode'];
        }

        $user = $this->getUser();

        if (!$user instanceof AccountUser) {
            return self::MODE_ANONYMOUS;
        }

        return null === $user->getAcademyId() ? self::MODE_PLATFORM : self::MODE_TENANT;
    }

    public function isAnonymous(): bool
    {
        return self::MODE_ANONYMOUS === $this->getMode();
    }

    public function isPlatform(): bool
    {
        return self::MODE_PLATFORM === $this->getMode();
    }

    public function isTenant(): bool
    {
        return self::MODE_TENANT === $this->getMode();
    }

    public function getUserId(): ?string
    {
        $context = $this->getContext();

        if ([] !== $context && array_key_exists('user_id', $context)) {
            return null === $context['user_id'] ? null : (string) $context['user_id'];
        }

        $user = $this->getUser();

        return $user instanceof AccountUser ? $user->getId() : null;
    }

    public function getAcademyId(): ?string
    {
        $context = $this->getContext();

        if ([] !== $context && array_key_exists('academy_id', $context)) {
            return null === $context['academy_id'] ? null : (string) $context['academy_id'];
        }

        $user = $this->getUser();

        return $user instanceof AccountUser ? $user->getAcademyId() : null;
    }

    public function getRole(): ?string
    {
        $context = $this->getContext();

        if ([] !== $context && array_key_exists('role', $context)) {
            return null === $context['role'] ? null : (string) $context['role'];
        }

        $user = $this->getUser();

        return $user instanceof AccountUser ? $user->getRole() : null;
    }

    public function getRoles(): array
    {
        $context = $this->getContext();

        if ([] !== $context && array_key_exists('roles', $context) && is_array($context['roles'])) {
            return array_values(array_map('strval', $context['roles']));
        }

        $user = $this->getUser();

        return $user instanceof AccountUser ? $user->getRoles() : [];
    }

    public function requireAcademyId(): string
    {
        if (!$this->isTenant()) {
            throw new AccessDeniedHttpException('Tenant context required.');
        }

        $academyId = $this->getAcademyId();

        if (null === $academyId || '' === $academyId) {
            throw new AccessDeniedHttpException('Tenant context required.');
        }

        return $academyId;
    }

    private function getToken(): ?TokenInterface
    {
        return $this->tokenStorage->getToken();
    }

    private function getUser(): ?AccountUser
    {
        $token = $this->getToken();

        if (null === $token) {
            return null;
        }

        $user = $token->getUser();

        return $user instanceof AccountUser ? $user : null;
    }

    private function getContext(): array
    {
        $token = $this->getToken();

        if (null === $token || !$token->hasAttribute('tenant_context')) {
            return [];
        }

        $context = $token->getAttribute('tenant_context');

        return is_array($context) ? $context : [];
    }
}
