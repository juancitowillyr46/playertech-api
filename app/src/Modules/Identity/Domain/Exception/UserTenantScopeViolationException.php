<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Exception;

use DomainException;

final class UserTenantScopeViolationException extends DomainException
{
    public function __construct()
    {
        parent::__construct('La operación no es válida para el contexto actual.');
    }
}
