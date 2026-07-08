<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Exception;

use App\Shared\Domain\Exception\ConflictException;

final class PlayerGuardianAlreadyExistsException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('La relación jugador-acudiente ya existe.');
    }
}
