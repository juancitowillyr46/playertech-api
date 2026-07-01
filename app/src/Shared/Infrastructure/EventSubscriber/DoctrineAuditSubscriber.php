<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventSubscriber;

use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

final class DoctrineAuditSubscriber implements EventSubscriber
{
    public function __construct(
        private readonly TenantContext $tenantContext
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $entityManager = $args->getObjectManager();
        $unitOfWork = $entityManager->getUnitOfWork();
        $actorId = $this->tenantContext->getUserId();

        foreach ($unitOfWork->getScheduledEntityInsertions() as $entity) {
            if (!$entity instanceof Auditable) {
                continue;
            }

            if (null === $entity->auditTrail()) {
                $entity->setAuditTrail(AuditTrail::create($actorId));
            }

            $classMetadata = $entityManager->getClassMetadata($entity::class);
            $unitOfWork->recomputeSingleEntityChangeSet($classMetadata, $entity);
        }

        foreach ($unitOfWork->getScheduledEntityUpdates() as $entity) {
            if (!$entity instanceof Auditable) {
                continue;
            }

            $auditTrail = $entity->auditTrail();

            if (null === $auditTrail) {
                $entity->setAuditTrail(AuditTrail::create($actorId));
            } elseif (null !== $actorId) {
                $auditTrail->touch($actorId);
            }

            $classMetadata = $entityManager->getClassMetadata($entity::class);
            $unitOfWork->recomputeSingleEntityChangeSet($classMetadata, $entity);
        }
    }
}
