<?php

declare(strict_types=1);

namespace App\Modules\Academy\Domain\Exception;

use App\Shared\Domain\Exception\ConflictException;

final class AcademyAlreadyExistsException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('El correo de contacto ya existe.');
    }
}
