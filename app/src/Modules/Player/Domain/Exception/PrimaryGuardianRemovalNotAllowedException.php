<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Exception;

use App\Shared\Domain\Exception\ConflictException;

final class PrimaryGuardianRemovalNotAllowedException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('No se puede eliminar el acudiente principal sin reasignarlo primero.');
    }
}
