<?php

declare(strict_types=1);

namespace App\Modules\Venue\Domain\Exception;

use App\Shared\Domain\Exception\ConflictException;

final class VenueAlreadyExistsException extends ConflictException
{
    public function __construct()
    {
        parent::__construct(
            'A venue with the same name already exists for this academy.'
        );
    }
}
