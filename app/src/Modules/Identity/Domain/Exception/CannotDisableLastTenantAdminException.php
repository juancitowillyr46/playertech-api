<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Exception;

use DomainException;

final class CannotDisableLastTenantAdminException extends DomainException
{
    public function __construct()
    {
        parent::__construct('No se puede desactivar el último administrador activo del tenant.');
    }
}
