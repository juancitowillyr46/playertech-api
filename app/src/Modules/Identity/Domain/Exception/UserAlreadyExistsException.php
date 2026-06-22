<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Exception;

use DomainException;

final class UserAlreadyExistsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('El correo electrónico ya existe.');
    }
}
