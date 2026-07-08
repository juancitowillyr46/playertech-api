<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;

final class PlayerGuardianNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('La relación jugador-acudiente no fue encontrada.');
    }
}
