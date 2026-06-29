<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Exception;

use App\Shared\Domain\Exception\ForbiddenException;

final class UserTenantScopeViolationException extends ForbiddenException
{
    public function __construct()
    {
        parent::__construct('La operación no es válida para el contexto actual.');
    }
}
