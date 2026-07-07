<?php

declare(strict_types=1);

namespace App\Modules\Team\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;

final class TeamNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('team not found');
    }
}
