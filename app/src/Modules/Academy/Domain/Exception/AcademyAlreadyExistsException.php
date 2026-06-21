<?php

declare(strict_types=1);

namespace App\Modules\Academy\Domain\Exception;

use DomainException;

final class AcademyAlreadyExistsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('El correo de contacto ya existe.');
    }
}
