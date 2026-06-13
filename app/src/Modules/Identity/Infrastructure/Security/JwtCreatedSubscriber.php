<?php

namespace App\Modules\Identity\Infrastructure\Security;

use App\Modules\Identity\Domain\User\AccountUser;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class JwtCreatedSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_CREATED => 'onJwtCreated',
        ];
    }

    public function onJwtCreated(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();

        if (!$user instanceof AccountUser) {
            return;
        }

        $data = $event->getData();
        $data['user_id'] = $user->getId();
        $data['academy_id'] = $user->getAcademyId();
        $data['role'] = $user->getRole();
        $data['roles'] = $user->getRoles();

        $event->setData($data);
    }
}
