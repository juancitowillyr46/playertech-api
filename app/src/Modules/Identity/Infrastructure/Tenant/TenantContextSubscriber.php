<?php

declare(strict_types=1);

namespace App\Modules\Identity\Infrastructure\Tenant;

use App\Modules\Identity\Domain\User\AccountUser;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final class TenantContextSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_AUTHENTICATED => 'onJwtAuthenticated',
        ];
    }

    public function onJwtAuthenticated(JWTAuthenticatedEvent $event): void
    {
        $token = $event->getToken();
        $user = $token->getUser();

        if (!$user instanceof AccountUser) {
            return;
        }

        $payload = $event->getPayload();
        $roles = $user->getRoles();
        $isRoot = in_array(AccountUser::ROLE_ROOT, $roles, true);
        $academyId = $user->getAcademyId();
        $payloadAcademyId = $payload['academy_id'] ?? null;

        if ($isRoot) {
            if (null !== $academyId || null !== $payloadAcademyId) {
                throw new AccessDeniedHttpException('ROLE_ROOT debe operar sin academy_id.');
            }

            $mode = TenantContext::MODE_PLATFORM;
        } else {
            if (null === $academyId || '' === $academyId) {
                throw new AccessDeniedHttpException('El usuario tenant debe tener academy_id.');
            }

            if (null === $payloadAcademyId || '' === $payloadAcademyId) {
                throw new AccessDeniedHttpException('El JWT del usuario tenant debe incluir academy_id.');
            }

            if ((string) $payloadAcademyId !== $academyId) {
                throw new AccessDeniedHttpException('El academy_id del JWT no coincide con el usuario autenticado.');
            }

            $mode = TenantContext::MODE_TENANT;
        }

        $token->setAttribute('tenant_context', [
            'mode' => $mode,
            'user_id' => $user->getId(),
            'academy_id' => $academyId,
            'role' => $user->getRole(),
            'roles' => $roles,
        ]);
    }
}
