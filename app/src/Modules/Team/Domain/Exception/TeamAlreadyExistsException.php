<?php

declare(strict_types=1);

namespace App\Modules\Team\Domain\Exception;

use App\Shared\Domain\Exception\ConflictException;

final class TeamAlreadyExistsException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('Ya existe un equipo con el mismo nombre en la categoría.');
    }
}
