<?php

declare(strict_types=1);

namespace App\Modules\Venue\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;

final class VenueNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct(
            'venue not found'
        );
    }
}
