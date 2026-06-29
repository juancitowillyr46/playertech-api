<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;

final class PlayerNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('player not found');
    }
}
