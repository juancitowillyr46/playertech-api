<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Exception;

use App\Shared\Domain\Exception\ConflictException;

final class PlayerAlreadyExistsException extends ConflictException
{
    public function __construct(string $message = 'El número de documento ya existe para esta academia.')
    {
        parent::__construct($message);
    }
}
