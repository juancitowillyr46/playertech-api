<?php

namespace App\Modules\Identity\Infrastructure\Security;

use App\Modules\Identity\Domain\User\AccountUser;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class JwtCreatedSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            JWTCreatedEvent::class => 'onJwtCreated',
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
