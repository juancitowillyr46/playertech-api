<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventSubscriber;

use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Shared\Infrastructure\Persistence\Doctrine\Filter\TenantFilter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class DoctrineFilterSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TenantContext $tenantContext
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }
        
        $filters = $this->em->getFilters();

        if ($this->tenantContext->isTenant()) {
            /** @var TenantFilter $filter */
            $filter = $filters->enable('tenant');
            $filter->setTenantContext($this->tenantContext);
        }
    }
}
