<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Filter;

use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

final class TenantFilter extends SQLFilter
{
    private ?TenantContext $tenantContext = null;

    public function setTenantContext(TenantContext $tenantContext): void
    {
        $this->tenantContext = $tenantContext;
    }

    public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
    {
        if (null === $this->tenantContext || !$this->tenantContext->isTenant()) {
            return '';
        }

        $academyId = $this->tenantContext->getAcademyId();
        if (null === $academyId) {
            return '';
        }

        // The Academy entity itself is a special case.
        // A tenant should be able to see their own academy, but not others.
        if ($targetEntity->getReflectionClass()->getName() === Academy::class) {
            return sprintf('%s.id = %s', $targetTableAlias, $this->getConnection()->quote($academyId));
        }

        if (!$targetEntity->hasField('academyId')) {
            return '';
        }

        return sprintf('%s.academy_id = %s', $targetTableAlias, $this->getConnection()->quote($academyId));
    }
}
