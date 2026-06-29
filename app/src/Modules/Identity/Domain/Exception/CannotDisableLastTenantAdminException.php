<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Exception;

use App\Shared\Domain\Exception\ConflictException;

final class CannotDisableLastTenantAdminException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('No se puede desactivar el último administrador activo del tenant.');
    }
}
