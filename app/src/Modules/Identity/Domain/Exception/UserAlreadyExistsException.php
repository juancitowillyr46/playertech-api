<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Exception;

use App\Shared\Domain\Exception\ConflictException;

final class UserAlreadyExistsException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('El correo electrónico ya existe.');
    }
}
