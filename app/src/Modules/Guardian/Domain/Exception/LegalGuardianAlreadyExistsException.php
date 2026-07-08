<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Domain\Exception;

use App\Shared\Domain\Exception\ConflictException;

final class LegalGuardianAlreadyExistsException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('El acudiente ya existe.');
    }
}
