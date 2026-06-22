<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Handler;

use App\Modules\Academy\Application\Query\GetAcademyContextQuery;
use App\Modules\Academy\Application\Response\AcademyContextResponse;
use App\Modules\Identity\Infrastructure\Tenant\TenantContext;

final readonly class GetAcademyContextHandler
{
    public function __construct(
        private TenantContext $tenantContext,
    ) {
    }

    public function __invoke(GetAcademyContextQuery $query): AcademyContextResponse
    {
        return new AcademyContextResponse(
            $this->tenantContext->getMode(),
            $this->tenantContext->getUserId(),
            $this->tenantContext->requireAcademyId(),
            $this->tenantContext->getRole(),
            $this->tenantContext->getRoles(),
        );
    }
}
