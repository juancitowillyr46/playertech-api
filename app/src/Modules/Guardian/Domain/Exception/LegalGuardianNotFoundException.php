<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;

final class LegalGuardianNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('El acudiente no fue encontrado.');
    }
}
