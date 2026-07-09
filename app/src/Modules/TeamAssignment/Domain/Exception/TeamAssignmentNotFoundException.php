<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;

final class TeamAssignmentNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('The team assignment was not found.');
    }
}
