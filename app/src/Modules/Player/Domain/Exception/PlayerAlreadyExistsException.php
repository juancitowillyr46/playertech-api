<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Exception;

use App\Shared\Domain\Exception\ConflictException;

final class PlayerAlreadyExistsException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('El número de documento ya existe para esta academia.');
    }
}
