<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Domain\Exception;

use App\Shared\Domain\Exception\ConflictException;

final class TeamAssignmentAlreadyExistsException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('The team assignment already exists.');
    }
}
